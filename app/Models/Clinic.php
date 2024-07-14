<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class, 'clinic_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'clinic_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class, 'clinic_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'clinic_id');
    }


    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function packages()
    {
        return $this->hasMany(Package::class, 'clinic_id');
    }
}
