<?php

namespace Database\Seeders;

use App\Models\JenisGaji;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisGajiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisGaji::create([
            'sistem_gaji' => 'Harian',
            'upah' => 100000
        ]);
    }
}
