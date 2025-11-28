<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';
    protected $primaryKey = 'id_pengaturan';
    protected $fillable = [
        'nama',
        'label',
        'nilai',
        'tipe'
    ];
    public static function getValue($nama)
    {
        $setting = self::where('nama', $nama)->first();
        if (!$setting) return null;

        return match ($setting->tipe ?? 'string') {
            'integer' => (int) $setting->nilai,
            'boolean' => filter_var($setting->nilai, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($setting->nilai, true),
            default => $setting->nilai,
        };
    }
}
