<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;
    protected $table = 'gaji';
    protected $primaryKey = 'id_gaji';
    protected $fillable = [
        'karyawan_id',
        'periode_bulan',
        'periode_tahun',
        'total_hari_kerja',
        'total_lembur',
        'potongan',
        'total_gaji',
        'tgl_dibayar',
        'status',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }
}
