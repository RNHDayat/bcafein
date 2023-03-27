<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowCategory extends Model
{
    use HasFactory;

    const isFOLLOWED = 0;
    const isUNFOLLOWED = 1;
    const isBLOCKED = 2;

    protected $table = 'follow_categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'following_id',
        'id_user',
        'follow_status',
    ];
}
