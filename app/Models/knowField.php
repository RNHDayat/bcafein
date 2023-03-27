<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class knowField extends Model
{
    use HasFactory;

    const isUNVALIDATED = 0;
    const isVALIDATED = 1;

    protected $table = 'know_fields';
    protected $primaryKey = 'id';
    protected $fillable = [
        'codeIlmu',
        'name',
        'validation',
        'id_user_propose',
        'id_user_validator',
    ];
}
