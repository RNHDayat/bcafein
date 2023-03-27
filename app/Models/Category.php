<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    const isUNVALIDATED = 0;
    const isVALIDATED = 1;

    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'codeCategory',
        'name',
        'validation',
        'id_user_propose',
        'id_user_validator',
    ];
}
