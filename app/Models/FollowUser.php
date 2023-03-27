<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUser extends Model
{
    use HasFactory;

    const isWAITING = 0;
    const isFOLLOWED = 1;
    const isBLOCKED = 2;
    const isUNFOLLOWED = 3;
}
