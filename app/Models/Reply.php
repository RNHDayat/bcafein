<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $table = 'replies';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_postings',
        'toAnswer_posting',
    ];
}
