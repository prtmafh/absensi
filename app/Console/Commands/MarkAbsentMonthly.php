<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Karyawan;
use App\Models\Absen;
use Carbon\Carbon;

class MarkAbsentMonthly extends Command
{
    protected $signature = 'absensi:mark-absent-monthly {bulan?} {tahun?}';
    protected $description = 'Menandai karyawan yang tidak absen pada tiap hari kerja dalam bulan tertentu sebagai tidak hadir (kecuali Sabtu & Minggu, dan tanggal setelah hari ini).';

    public function handle()
    {
        $bulan = $this->argument('bulan') ?? Carbon::now()->month;
        $tahun = $this->argument('tahun') ?? Carbon::now()->year;

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth();

        // 🔥 Batasi agar tidak melebihi hari ini
        $today = Carbon::today();
        if ($endDate->gt($today)) {
            $endDate = $today;
        }

        $karyawans = Karyawan::pluck('id_karyawan');

        $this->info("📅 Memproses absensi dari {$startDate->toDateString()} sampai {$endDate->toDateString()}");

        foreach ($karyawans as $id) {
            $tanggal = $startDate->copy();

            while ($tanggal->lte($endDate)) {

                // ⛔ Skip hari Sabtu (6) dan Minggu (0)
                if (in_array($tanggal->dayOfWeek, [0, 6])) {
                    $this->line("🟡 {$tanggal->toDateString()} adalah hari " .
                        ($tanggal->isSunday() ? 'Minggu' : 'Sabtu') .
                        " — dilewati.");
                    $tanggal->addDay();
                    continue;
                }

                // 💾 Cek apakah sudah ada absen untuk tanggal tersebut
                $cek = Absen::where('karyawan_id', $id)
                    ->whereDate('tanggal', $tanggal->toDateString())
                    ->exists();

                if (!$cek) {
                    Absen::create([
                        'karyawan_id' => $id,
                        'tanggal' => $tanggal->toDateString(),
                        'status' => 'tidak hadir',
                    ]);
                    $this->line("❌ Karyawan ID {$id} tanggal {$tanggal->toDateString()} → tidak hadir");
                }

                $tanggal->addDay();
            }
        }

        $this->info("✅ Selesai! Semua hari kerja (Senin–Jumat) hingga {$endDate->toDateString()} telah diperiksa.");
        return 0;
    }
}
