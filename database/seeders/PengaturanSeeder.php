<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengaturan;

class PengaturanSeeder extends Seeder
{
    /**
     * Jalankan seeder pengaturan sistem.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'shift_start', 'label' => 'Jam Masuk', 'nilai' => '08:00', 'tipe' => 'time'],
            ['nama' => 'shift_end', 'label' => 'Jam Pulang', 'nilai' => '17:00', 'tipe' => 'time'],
            ['nama' => 'tarif_lembur', 'label' => 'Tarif Lembur per Jam', 'nilai' => '15000', 'tipe' => 'integer'],
            ['nama' => 'late_tolerance_minutes', 'label' => 'Toleransi Keterlambatan (menit)', 'nilai' => '10', 'tipe' => 'integer'],
            ['nama' => 'work_minutes_per_day', 'label' => 'Durasi Kerja per Hari (menit)', 'nilai' => '480', 'tipe' => 'integer'],
            ['nama' => 'daily_divisor', 'label' => 'Pembagi Gaji Bulanan ke Harian', 'nilai' => '25', 'tipe' => 'integer'],
            ['nama' => 'late_penalty_mode', 'label' => 'Jenis Denda Keterlambatan', 'nilai' => 'proportional', 'tipe' => 'string'],
            ['nama' => 'late_flat_per_minutes', 'label' => 'Kelipatan Menit Denda Flat', 'nilai' => '5', 'tipe' => 'integer'],
            ['nama' => 'late_flat_amount', 'label' => 'Jumlah Denda Flat per Kelipatan', 'nilai' => '2000', 'tipe' => 'integer'],
            ['nama' => 'deduct_izin_for_monthly', 'label' => 'Potong Gaji Bulanan Saat Izin?', 'nilai' => 'false', 'tipe' => 'boolean'],
        ];

        foreach ($data as $item) {
            Pengaturan::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}
