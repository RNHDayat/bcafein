<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    use HasFactory;

    const HIDDEN = 1;
    const UNHIDE = 0;

    protected $table = 'credentials';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_employee',
        'type',
        'description',
        'hide',
    ];
}
