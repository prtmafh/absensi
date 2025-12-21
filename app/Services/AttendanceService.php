<?php

namespace App\Services;

use App\Models\Absen;
use App\Models\Karyawan;
use App\Models\Izin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Hitung range tanggal & total hari yang berjalan + total hari setahun
     */
    protected function getDateInfo(int $tahun): array
    {
        $today = Carbon::today();
        $start = Carbon::create($tahun, 1, 1)->startOfDay();
        $endOfYear = Carbon::create($tahun, 12, 31)->endOfDay();

        // Total hari dalam 1 tahun (365 / 366)
        $totalDaysYear = (int) Carbon::create($tahun, 12, 31)->dayOfYear;

        // Jika tahun masih berjalan → hitung sampai hari ini
        if ($tahun == $today->year) {
            $endCount = $today->endOfDay();
        }
        // Jika tahun sudah lewat → hitung full 1 tahun
        elseif ($tahun < $today->year) {
            $endCount = $endOfYear;
        }
        // Jika tahun belum datang → belum ada hari berjalan
        else {
            return [
                'start' => $start,
                'end' => $start,
                'totalDaysCount' => 0,
                'totalDaysYear' => $totalDaysYear,
            ];
        }

        // Total hari yang dihitung (hari yang sudah berjalan)
        $totalDaysCount = (int) ($start->diffInDays($endCount) + 1);

        return [
            'start' => $start,
            'end' => $endCount,
            'totalDaysCount' => $totalDaysCount,
            'totalDaysYear' => $totalDaysYear,
        ];
    }

    /**
     * Rekap untuk semua karyawan (untuk tabel list/index)
     */
    public function getYearlyRecap(int $tahun): array
    {
        $info = $this->getDateInfo($tahun);

        $start = $info['start'];
        $end = $info['end'];
        $totalDaysCount = (int) $info['totalDaysCount']; // contoh: 342
        $totalDaysYear  = (int) $info['totalDaysYear'];  // contoh: 365

        $hadirStatuses = ['hadir', 'terlambat'];

        // Hari hadir per karyawan (hadir+terlambat)
        $hadirPerKaryawan = Absen::select(
            'karyawan_id',
            DB::raw('COUNT(DISTINCT tanggal) as total_hadir')
        )
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->whereIn('status', $hadirStatuses)
            ->groupBy('karyawan_id')
            ->pluck('total_hadir', 'karyawan_id');

        $result = [];
        $karyawans = Karyawan::all();

        foreach ($karyawans as $karyawan) {
            $hadirDays = (int) ($hadirPerKaryawan[$karyawan->id_karyawan] ?? 0);

            // Hari tidak hadir (berdasarkan hari berjalan)
            $absentDays = max(0, $totalDaysCount - $hadirDays);

            $percentage = $totalDaysCount > 0
                ? ($hadirDays / $totalDaysCount) * 100
                : 0;

            $result[] = [
                'karyawan_id'      => $karyawan->id_karyawan,
                'nama'             => $karyawan->nama,
                'total_hari'       => $totalDaysCount,
                'total_hari_tahun' => $totalDaysYear,
                'hari_hadir'       => $hadirDays,
                'hari_tidak_hadir' => $absentDays,
                'persentase'       => round($percentage, 2),
            ];
        }

        return $result;
    }

    /**
     * Rekap detail untuk satu karyawan (untuk halaman show)
     */
    public function getYearlyRecapForKaryawan(int $tahun, int $karyawanId): array
    {
        $info = $this->getDateInfo($tahun);

        $start = $info['start'];
        $end = $info['end'];
        $totalDaysCount = (int) $info['totalDaysCount'];
        $totalDaysYear  = (int) $info['totalDaysYear'];

        // Tahun masa depan
        if ($totalDaysCount === 0) {
            return [
                'karyawan_id' => $karyawanId,
                'tahun' => $tahun,
                'total_hari' => 0,
                'total_hari_tahun' => $totalDaysYear,

                'hari_hadir' => 0,
                'total_hadir' => 0,
                'total_terlambat' => 0,

                'total_izin' => 0,
                'izin_by_jenis' => [],
                'izin_list' => [],

                'total_tidak_hadir_status' => 0,
                'total_alpha_tanpa_keterangan' => 0,

                'persentase' => 0,
            ];
        }

        // ====== HADIR ======
        $totalHadir = Absen::where('karyawan_id', $karyawanId)
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->where('status', 'hadir')
            ->distinct()
            ->count('tanggal');

        // ====== TERLAMBAT ======
        $totalTerlambat = Absen::where('karyawan_id', $karyawanId)
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->where('status', 'terlambat')
            ->distinct()
            ->count('tanggal');

        // Hari hadir untuk persentase
        $hadirDays = (int) ($totalHadir + $totalTerlambat);

        // ====== IZIN (tabel izin, disetujui) ======
        $izinQuery = Izin::where('karyawan_id', $karyawanId)
            ->whereBetween('tanggal_izin', [$start->toDateString(), $end->toDateString()])
            ->where('status', 'disetujui');

        $totalIzin = (clone $izinQuery)
            // ->distinct()
            ->count('tanggal_izin');

        $izinByJenis = (clone $izinQuery)
            ->select('jenis_izin', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_izin')
            ->pluck('total', 'jenis_izin')
            ->toArray();

        $izinList = (clone $izinQuery)
            ->orderBy('tanggal_izin', 'asc')
            ->get(['tanggal_izin', 'jenis_izin', 'keterangan', 'status'])
            ->map(function ($row) {
                return [
                    'tanggal_izin' => $row->tanggal_izin?->format('Y-m-d') ?? '-',
                    'jenis_izin' => $row->jenis_izin,
                    'keterangan' => $row->keterangan,
                    'status' => $row->status,
                ];
            })
            ->toArray();

        // ====== TIDAK HADIR (status di tabel absen) ======
        $totalTidakHadirStatus = Absen::where('karyawan_id', $karyawanId)
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->where('status', 'tidak hadir')
            ->distinct()
            ->count('tanggal');

        // ====== ALPHA TANPA KETERANGAN ======
        // definisi: hari berjalan - (hadir+terlambat) - izin(disetujui)
        // sisa ini mencakup hari tanpa absen & status tidak hadir.
        $alphaTanpaKeterangan = $totalDaysCount - ($hadirDays + $totalIzin);
        if ($alphaTanpaKeterangan < 0) $alphaTanpaKeterangan = 0;

        // ====== PERSENTASE ======
        $percentage = $totalDaysCount > 0 ? ($hadirDays / $totalDaysCount) * 100 : 0;

        return [
            'karyawan_id' => $karyawanId,
            'tahun' => $tahun,

            // tampil 342 / 365
            'total_hari' => $totalDaysCount,
            'total_hari_tahun' => $totalDaysYear,

            'hari_hadir' => $hadirDays,
            'total_hadir' => (int) $totalHadir,
            'total_terlambat' => (int) $totalTerlambat,

            'total_izin' => (int) $totalIzin,
            'izin_by_jenis' => $izinByJenis,
            'izin_list' => $izinList,

            'total_tidak_hadir_status' => (int) $totalTidakHadirStatus,
            'total_alpha_tanpa_keterangan' => (int) $alphaTanpaKeterangan,

            'persentase' => round($percentage, 2),
        ];
    }
}
