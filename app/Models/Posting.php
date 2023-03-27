<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posting extends Model
{
    use HasFactory;

    const isHIDDEN = 0;
    const isACTIVE = 1;
    const isDRAFTED = 2;
    const isBEST_ANSWER = 3;
    const isBLOCKED = 4;

    protected $table = 'postings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'id_credential',
        'id_category',
        'title',
        'title_slug',
        'description',
        'image',
        'status',
    ];
}
