<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneCountry extends Model
{
    use HasFactory;

    protected $table = 'phone_countries';
    protected $primaryKey = 'id';
    protected $fillable = [
        'iso',
        'name',
        'nicename',
        'iso3',
        'numcode',
        'phonecode',
    ];
    public $timestamps = false;
}
