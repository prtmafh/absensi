<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Karyawan::create([
            'jabatan_id' => 1,
            'nama' => 'Beni Nur Rahmat',
            'alamat' => 'Jl. Ciketing Rawamulya, Mustika Jaya, Kota Bekasi',
            'no_hp' => '081234567890',
            'tgl_masuk' => Carbon::create('2023', '05', '15'),
            'jenis_gaji_id' => 1, // misal: 1 = Harian
            'status' => 'aktif',
        ]);
    }
}
