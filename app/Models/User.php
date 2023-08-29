<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    // Variabel Konstan untuk level user
    const DIRECTOR = 0;
    const SUPER_ADMIN = 1;
    const ADMINS = 2;
    const LEAD_STUDY = 3;
    const DOMINANTS = 4;
    const USERS = 5;

    const ACTIVE = 1;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'account_name',
        'email',
        'password',
        'email_verified_at',
        'imageava',
        'status',
        'level',
        'firebase_token',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function employees()
    {
        return $this->hasOne(Employee::class, 'id_user');
    }
    
    public function followUser()
    {
        return $this->hasMany(FollowUser::class, 'id_user');
    }
    public function votePosting()
    {
        return $this->hasMany(Vote::class, 'id_user');
    }
}
