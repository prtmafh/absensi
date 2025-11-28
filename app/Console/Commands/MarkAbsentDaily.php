<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Karyawan;
use App\Models\Absen;
use Carbon\Carbon;

class MarkAbsentDaily extends Command
{
    protected $signature = 'absensi:mark-absent';
    protected $description = 'Menandai karyawan yang tidak absen hari ini sebagai tidak hadir';

    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $karyawans = Karyawan::pluck('id_karyawan');

        foreach ($karyawans as $id) {
            $cek = Absen::where('karyawan_id', $id)
                ->whereDate('tanggal', $today)
                ->exists();

            if (!$cek) {
                Absen::create([
                    'karyawan_id' => $id,
                    'tanggal' => $today,
                    'status' => 'tidak hadir',
                ]);
                $this->info("Karyawan ID {$id} ditandai tidak hadir.");
            }
        }

        $this->info("✅ Proses selesai — semua karyawan yang tidak absen ditandai 'tidak hadir'.");
        return 0;
    }
}
