<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Militaries extends Model
{
    use HasFactory;

    protected $table = 'militaries';

    protected $fillable = [
        'nama',
        'jenis',
        'type',
        'kondisi',
        'tahun_produksi',
        'tanggal_perolehan',
        'matra',
        'gambar',
    ];
}
