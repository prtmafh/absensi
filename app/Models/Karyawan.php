<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'jabatan_id',
        'nama',
        'alamat',
        'no_hp',
        'tgl_masuk',
        'jenis_gaji_id',
        'status',
        'foto',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function jenisGaji()
    {
        return $this->belongsTo(JenisGaji::class, 'jenis_gaji_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'karyawan_id', 'id_karyawan');
    }
    public function absen()
    {
        return $this->hasMany(Absen::class, 'karyawan_id', 'id_karyawan');
    }
    public function lembur()
    {
        return $this->hasMany(Lembur::class, 'karyawan_id', 'id_karyawan');
    }
    public function izin()
    {
        return $this->hasMany(Izin::class, 'karyawan_id', 'id_karyawan');
    }
}
