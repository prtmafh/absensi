<?php

namespace App\Services;

use App\Models\Karyawan;
use App\Models\Absen;
use Carbon\Carbon;

class MarkAbsentService
{
    public function markMonthly($bulan = null, $tahun = null)
    {
        $bulan = $bulan ?? Carbon::now()->month;
        $tahun = $tahun ?? Carbon::now()->year;

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth();

        $karyawans = Karyawan::pluck('id_karyawan');
        $summary = [];

        foreach ($karyawans as $id) {
            $tanggal = $startDate->copy();

            while ($tanggal->lte($endDate)) {
                if ($tanggal->isSunday()) {
                    $tanggal->addDay();
                    continue;
                }

                $cek = Absen::where('id_karyawan', $id)
                    ->whereDate('tanggal', $tanggal->toDateString())
                    ->exists();

                if (!$cek) {
                    Absen::create([
                        'id_karyawan' => $id,
                        'tanggal' => $tanggal->toDateString(),
                        'status' => 'tidak hadir',
                    ]);
                    $summary[] = "Karyawan ID {$id} tanggal {$tanggal->toDateString()} → tidak hadir";
                }

                $tanggal->addDay();
            }
        }

        return $summary;
    }
}
