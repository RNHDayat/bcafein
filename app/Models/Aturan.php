<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aturan extends Model
{
    use HasFactory;

    protected $table = 'aturan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_jenis',
        'id_kategori',
        'nomor',
        'tahun',
        'tgl_terbit',
        'short_desc',
        'keterangan',
        'doc',
        'oleh',
    ];
    protected $casts = [
        'id_kategori' => 'json',
    ];
}
