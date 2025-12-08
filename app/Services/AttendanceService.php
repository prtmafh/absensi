<?php

namespace App\Services;

use App\Models\Absen;
use App\Models\Karyawan;
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
        $start = Carbon::create($tahun, 1, 1);
        $endOfYear = Carbon::create($tahun, 12, 31);

        // Total hari dalam 1 tahun (365 / 366)
        $totalDaysYear = $endOfYear->dayOfYear;

        // Jika tahun masih berjalan → hitung sampai hari ini
        if ($tahun == $today->year) {
            $endCount = $today;
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
                'totalDaysYear' => $totalDaysYear
            ];
        }

        // Total hari yang dihitung (hari yang sudah berjalan)
        $totalDaysCount = $start->diffInDays($endCount) + 1;

        return [
            'start' => $start,
            'end' => $endCount,
            'totalDaysCount' => $totalDaysCount,
            'totalDaysYear' => $totalDaysYear
        ];
    }

    /**
     * Rekap untuk semua karyawan
     */
    public function getYearlyRecap(int $tahun): array
    {
        $info = $this->getDateInfo($tahun);

        $start = $info['start'];
        $end = $info['end'];
        $totalDaysCount = $info['totalDaysCount']; // contoh: 342
        $totalDaysYear = $info['totalDaysYear'];   // contoh: 365

        $hadirStatuses = ['hadir', 'terlambat'];

        // Hitung kehadiran
        $hadirPerKaryawan = Absen::select(
            'karyawan_id',
            DB::raw('COUNT(DISTINCT tanggal) as total_hadir')
        )
            ->whereBetween('tanggal', [$start, $end])
            ->whereIn('status', $hadirStatuses)
            ->groupBy('karyawan_id')
            ->pluck('total_hadir', 'karyawan_id');

        $result = [];
        $karyawans = Karyawan::all();

        foreach ($karyawans as $karyawan) {
            $hadirDays = (int) ($hadirPerKaryawan[$karyawan->id_karyawan] ?? 0);

            // Hari tidak hadir = hari berjalan - hari hadir
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
     * Rekap untuk satu karyawan
     */
    public function getYearlyRecapForKaryawan(int $tahun, int $karyawanId): array
    {
        $info = $this->getDateInfo($tahun);

        $start = $info['start'];
        $end = $info['end'];
        $totalDaysCount = $info['totalDaysCount'];
        $totalDaysYear = $info['totalDaysYear'];

        $hadirStatuses = ['hadir', 'terlambat'];

        $hadirDays = Absen::where('karyawan_id', $karyawanId)
            ->whereBetween('tanggal', [$start, $end])
            ->whereIn('status', $hadirStatuses)
            ->distinct()
            ->count('tanggal');

        $absentDays = max(0, $totalDaysCount - $hadirDays);

        $percentage = $totalDaysCount > 0
            ? ($hadirDays / $totalDaysCount) * 100
            : 0;

        return [
            'karyawan_id'      => $karyawanId,
            'tahun'            => $tahun,
            'total_hari'       => $totalDaysCount,
            'total_hari_tahun' => $totalDaysYear,
            'hari_hadir'       => $hadirDays,
            'hari_tidak_hadir' => $absentDays,
            'persentase'       => round($percentage, 2),
        ];
    }
}
