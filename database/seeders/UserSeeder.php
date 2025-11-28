<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin default
        // DB::table('users')->insert([
        //     'username'    => 'admin1',
        //     'password'    => Hash::make('password123'),
        //     'role_user'   => 'admin',
        //     'karyawan_id' => null,
        //     'status'      => 'aktif',
        // ]);
        User::create([
            'username' => 'admin1',
            'password' =>  Hash::make('password123'), // auto-hash oleh Laravel
            'role_user' => 'admin',
            'karyawan_id' => null,
            'status' => 'aktif',
        ]);

        // Contoh karyawan (asumsi id_karyawan = 1 sudah ada di tabel karyawan)
        // DB::table('users')->insert([
        //     'username'    => 'karyawan1',
        //     'password'    => Hash::make('password123'),
        //     'role_user'   => 'karyawan',
        //     'karyawan_id' => 1,
        //     'status'      => 'aktif',
        //     'created_at'  => now(),
        //     'updated_at'  => now(),
        // ]);
    }
}
