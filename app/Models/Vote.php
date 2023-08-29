<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    const isWAITING = 0;
    const isVOTE = 1;
    const isDOWNVOTE = 2;
    protected $table = 'votes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'id_postings',
        'vote_status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
