<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;
    const isWAITING = 0;
    const isREAD = 1;
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'title',
        'body',
        'status_read',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
