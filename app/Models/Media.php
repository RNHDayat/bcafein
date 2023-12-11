<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_media',
        'no_volume',
        'tahun',
        'tgl_terbit',
        'headline',
        'cover',
        'attachment',
        'keterangan',
        'oleh',
    ];
}
