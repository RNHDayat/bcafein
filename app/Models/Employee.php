<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'fullname',
        'nickname',
        'familyname',
        'gender',
        'datebirth',
        'birthplace',
        'employee_status',
        'phone',
        'country_phone',
        'country',
        'description',
        // Current Job
        'company',
        'job_position',
        'start_year',
        'end_year',
        'npwp',
        // Address Home
        'lat_house',
        'long_house',
        'address_house',
        // QR Profile
        'qrcode_path',
        'qrcode_link',
    ];

    // INCOME RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    

    public function birthplace()
    {
        return $this->belongsTo(City::class, 'birthplace');
    }

    public function countryPhone()
    {
        return $this->belongsTo(PhoneCountry::class, 'country_phone');
    }

    // OUTCOME RELATIONSHIPS
    public function employeesEducation()
    {
        return $this->hasMany(Education::class, 'id_employee');
    }

    public function employeesCredential()
    {
        return $this->hasMany(Credential::class, 'id_employee');
    }
}
