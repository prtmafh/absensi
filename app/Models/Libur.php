<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libur extends Model
{
    protected $table = 'libur_nasional';
    protected $primaryKey = 'id_libur';
    protected $fillable = [
        'tanggal',
        'nama',
        'keterangan',
    ];
    protected $casts = [
        'tanggal' => 'date',
    ];
}
