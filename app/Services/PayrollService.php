<?php

namespace App\Services;

use App\Models\Absen;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    /**
     * Ambil nilai pengaturan dari tabel `pengaturan`.
     */
    protected function getSetting(string $name, $default = null)
    {
        static $cache = [];

        if (isset($cache[$name])) {
            return $cache[$name];
        }

        $setting = Pengaturan::getValue($name);
        return $cache[$name] = $setting ?? $default;
    }


    public function generateForAll(int $bulan, int $tahun): array
    {
        $summary = ['created' => 0, 'skipped' => 0, 'details' => []];
        [$start, $end] = $this->getMonthRange($bulan, $tahun);

        $karyawans = Karyawan::with('jenisGaji')->get();

        DB::beginTransaction();
        try {
            foreach ($karyawans as $karyawan) {
                $already = Gaji::where('karyawan_id', $karyawan->id_karyawan)
                    ->where('periode_bulan', $bulan)
                    ->where('periode_tahun', $tahun)
                    ->exists();

                if ($already) {
                    $summary['skipped']++;
                    $summary['details'][] = [
                        'karyawan_id' => $karyawan->id_karyawan,
                        'nama' => $karyawan->nama ?? '-',
                        'status' => 'skipped (sudah ada gaji)',
                    ];
                    continue;
                }

                $calc = $this->calculateForOne($karyawan->id_karyawan, $bulan, $tahun);

                Gaji::create([
                    'karyawan_id' => $karyawan->id_karyawan,
                    'periode_bulan' => $bulan,
                    'periode_tahun' => $tahun,
                    'total_hari_kerja' => $calc['total_hadir'],
                    'total_lembur' => $calc['total_lembur_upah'],
                    'potongan' => $calc['potongan'],
                    'total_gaji' => $calc['total_gaji'],
                    'tgl_dibayar' => null,
                    'status' => 'proses',
                ]);

                $summary['created']++;
                $summary['details'][] = [
                    'karyawan_id' => $karyawan->id_karyawan,
                    'nama' => $karyawan->nama ?? '-',
                    'sistem_gaji' => $calc['sistem_gaji'],
                    'upah_dasar' => $calc['upah_dasar'],
                    'hadir' => $calc['total_hadir'],
                    'terlambat_hari' => $calc['total_terlambat'],
                    'late_minutes' => $calc['late_minutes'],
                    'late_penalty' => $calc['late_penalty'],
                    'izin' => $calc['total_izin'],
                    'alpha' => $calc['total_tidak_hadir'],
                    'lembur' => $calc['total_lembur_upah'],
                    'potongan' => $calc['potongan'],
                    'total_gaji' => $calc['total_gaji'],
                ];
            }

            DB::commit();
            return $summary;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function calculateForOne(int $karyawanId, int $bulan, int $tahun): array
    {
        [$start, $end] = $this->getMonthRange($bulan, $tahun);

        $karyawan = Karyawan::with('jenisGaji')->where('id_karyawan', $karyawanId)->firstOrFail();
        $jenis = $karyawan->jenisGaji;
        $sistem = strtolower($jenis?->sistem_gaji ?? 'bulanan');
        $upah = (float) ($jenis?->upah ?? 0);

        $absenQuery = Absen::where('karyawan_id', $karyawanId)
            ->whereBetween('tanggal', [$start, $end]);

        $totalHadirReg = (clone $absenQuery)->where('status', 'hadir')->count();
        $totalIzin = (clone $absenQuery)->where('status', 'izin')->count();
        $totalAlpha = (clone $absenQuery)->where('status', 'tidak hadir')->count();
        $totalTerlambat = (clone $absenQuery)->where('status', 'terlambat')->count();

        $totalHadirDibayar = $totalHadirReg + $totalTerlambat;

        $totalLemburUpah = (float) Lembur::where('karyawan_id', $karyawanId)
            ->whereBetween('tanggal', [$start, $end])
            ->where('status', 'disetujui')
            ->sum('total_upah');

        $totalLateMinutes = $this->sumLateMinutes($karyawanId, $bulan, $tahun);
        $rateHarian = $this->dailyRateFromMonthly($upah);
        $latePenalty = $this->latePenalty($sistem, $upah, $rateHarian, $totalLateMinutes);

        // Hitung gaji dasar & potongan hari
        if ($sistem === 'harian') {
            $upahDasar = $upah * $totalHadirDibayar;
            $potonganHari = 0;
        } else {
            $upahDasar = $upah;
            $hariPotong = $totalAlpha + ($this->deductIzin() ? $totalIzin : 0);
            $potonganHari = $rateHarian * $hariPotong;
        }

        $totalPotongan = $potonganHari + $latePenalty;
        $totalGaji = max(0, $upahDasar + $totalLemburUpah - $totalPotongan);

        return [
            'sistem_gaji' => $jenis?->sistem_gaji ?? 'Bulanan',
            'upah_dasar' => round($upahDasar, 2),
            'total_hadir' => $totalHadirDibayar,
            'total_hadir_reg' => $totalHadirReg,
            'total_terlambat' => $totalTerlambat,
            'total_izin' => $totalIzin,
            'total_tidak_hadir' => $totalAlpha,
            'total_lembur_upah' => round($totalLemburUpah, 2),
            'late_minutes' => $totalLateMinutes,
            'late_penalty' => round($latePenalty, 2),
            'potongan' => round($totalPotongan, 2),
            'total_gaji' => round($totalGaji, 2),
        ];
    }

    // ===== Helpers =====

    protected function sumLateMinutes(int $karyawanId, int $bulan, int $tahun): int
    {
        [$start, $end] = $this->getMonthRange($bulan, $tahun);

        $jadwalMasuk = $this->getSetting('shift_start', '08:00');
        $toleransi = (int) $this->getSetting('late_tolerance_minutes', 10);
        $tz = config('app.timezone', 'Asia/Jakarta');

        $records = Absen::where('karyawan_id', $karyawanId)
            ->whereBetween('tanggal', [$start, $end])
            ->where('status', 'terlambat')
            ->whereNotNull('jam_masuk')
            ->get(['tanggal', 'jam_masuk']);

        $total = 0;
        foreach ($records as $r) {
            $scheduled = Carbon::parse($r->tanggal . ' ' . $jadwalMasuk, $tz);
            $actual = Carbon::parse($r->tanggal . ' ' . $r->jam_masuk, $tz);

            if ($actual->gt($scheduled)) {
                $diff = $scheduled->diffInMinutes($actual);
                $late = max(0, $diff - $toleransi);
                $total += $late;
            }
        }
        return $total;
    }

    protected function latePenalty(string $sistem, float $upah, float $rateHarian, int $lateMinutes): float
    {
        if ($lateMinutes <= 0) return 0.0;

        $mode = $this->getSetting('late_penalty_mode', 'proportional');
        if ($mode === 'flat') {
            $stepMin = (int) $this->getSetting('late_flat_per_minutes', 5);
            $stepAmt = (float) $this->getSetting('late_flat_amount', 2000);
            $kelipatan = intdiv($lateMinutes + $stepMin - 1, $stepMin);
            return $kelipatan * $stepAmt;
        }

        $workMinutes = (int) $this->getSetting('work_minutes_per_day', 480);
        if ($workMinutes <= 0) return 0.0;

        if ($sistem === 'harian') {
            return $upah * ($lateMinutes / $workMinutes);
        }
        return $rateHarian * ($lateMinutes / $workMinutes);
    }

    protected function dailyRateFromMonthly(float $monthly): float
    {
        $divisor = (int) $this->getSetting('daily_divisor', 25);
        return $divisor > 0 ? ($monthly / $divisor) : 0.0;
    }

    protected function getMonthRange(int $bulan, int $tahun): array
    {
        $start = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
        $end = (clone $start)->endOfMonth()->endOfDay();
        return [$start->toDateString(), $end->toDateString()];
    }

    protected function deductIzin(): bool
    {
        return (bool) $this->getSetting('deduct_izin_for_monthly', false);
    }
}
