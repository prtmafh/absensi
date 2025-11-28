<?php

return [
    'shift_start' => '08:00', // jam masuk
    'late_tolerance_minutes' => 10, // toleransi keterlambatan
    'work_minutes_per_day' => 480, // 8 jam

    // Hitung rate harian dari gaji bulanan (25/26/30)
    'daily_divisor' => 25,

    // jenis denda keterlambatan: proportional | flat
    'late_penalty_mode' => 'proportional',

    // Jika flat: berapa rupiah per kelipatan menit
    'late_flat_per_minutes' => 5, // tiap 5 menit
    'late_flat_amount' => 2000, // Rp 2.000 per 5 menit

    // Potongan izin untuk bulanan?
    'deduct_izin_for_monthly' => false,
];
