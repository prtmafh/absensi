<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Karyawan;
use App\Models\Absen;
use App\Models\Libur;
use App\Models\Izin;
use Carbon\Carbon;

class MarkAbsentMonthly extends Command
{
    protected $signature = 'absensi:mark-absent-monthly {bulan?} {tahun?}';
    protected $description = 'Auto absen: skip Sabtu/Minggu & libur nasional. Jika ada izin disetujui → status izin, jika tidak → tidak hadir.';

    public function handle()
    {
        $bulan = (int) ($this->argument('bulan') ?? Carbon::now()->month);
        $tahun = (int) ($this->argument('tahun') ?? Carbon::now()->year);

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
        $endDate   = $startDate->copy()->endOfMonth();

        // 🔥 Jangan lewat hari ini
        $today = Carbon::today();
        if ($endDate->gt($today)) {
            $endDate = $today;
        }

        /* ===============================
           LIBUR NASIONAL (SET)
        =============================== */
        $liburSet = Libur::whereBetween('tanggal', [
            $startDate->toDateString(),
            $endDate->toDateString(),
        ])
            ->pluck('tanggal')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->flip(); // jadi set

        $karyawans = Karyawan::pluck('id_karyawan');

        $this->info("📅 Proses absensi {$startDate->toDateString()} s/d {$endDate->toDateString()}");
        $this->info("🏖️ Libur nasional: {$liburSet->count()} hari");

        foreach ($karyawans as $karyawanId) {
            $tanggal = $startDate->copy();

            while ($tanggal->lte($endDate)) {
                $tgl = $tanggal->toDateString();

                /* 1️⃣ Skip Sabtu & Minggu */
                if (in_array($tanggal->dayOfWeek, [0, 6])) {
                    $tanggal->addDay();
                    continue;
                }

                /* 2️⃣ Skip Libur Nasional */
                if ($liburSet->has($tgl)) {
                    $tanggal->addDay();
                    continue;
                }

                /* 3️⃣ Cek izin (1 hari) */
                $izin = Izin::where('karyawan_id', $karyawanId)
                    ->where('status', 'disetujui')
                    ->whereDate('tanggal_izin', $tgl)
                    ->first();

                $absen = Absen::where('karyawan_id', $karyawanId)
                    ->whereDate('tanggal', $tgl)
                    ->first();

                if ($izin) {
                    // Jika belum ada absen → buat izin
                    if (!$absen) {
                        Absen::create([
                            'karyawan_id' => $karyawanId,
                            'tanggal'     => $tgl,
                            'status'      => 'izin',
                        ]);
                        $this->line("🟦 {$karyawanId} {$tgl} → izin");
                    }
                    // Jika sudah ada & status tidak hadir → update jadi izin
                    elseif ($absen->status === 'tidak hadir') {
                        $absen->update(['status' => 'izin']);
                        $this->line("🟦 {$karyawanId} {$tgl} → update izin");
                    }

                    $tanggal->addDay();
                    continue;
                }

                /* 4️⃣ Tidak ada izin & belum ada absen */
                if (!$absen) {
                    Absen::create([
                        'karyawan_id' => $karyawanId,
                        'tanggal'     => $tgl,
                        'status'      => 'tidak hadir',
                    ]);
                    $this->line("❌ {$karyawanId} {$tgl} → tidak hadir");
                }

                $tanggal->addDay();
            }
        }

        $this->info("✅ Selesai! Absensi otomatis bulan {$bulan}-{$tahun} berhasil.");
        return 0;
    }
}
