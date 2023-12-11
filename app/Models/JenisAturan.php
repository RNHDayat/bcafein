<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisAturan extends Model
{
    use HasFactory;
    protected $table = 'jenis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'keterangan',
        'oleh',
    ];
}
