<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'number','email', 'phone','description','website','logo_url','registration_date','gst_number','status'];

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


    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'clinic_id');
    }

    public function packages()
    {
        return $this->hasMany(Package::class, 'clinic_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'clinic_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'clinic_id');
    }

    public function medicines()
    {
        return $this->hasMany(Medicine::class, 'clinic_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'clinic_id');
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'imageable');
    }

    public function logo()
    {
        return $this->morphOne(Asset::class, 'imageable')->where('type', 'LOGO');;
    }

    public function favicon()
    {
        return $this->morphOne(Asset::class, 'imageable')->where('type', 'FAVICON');;
    }

    public function scanner()
    {
        return $this->morphOne(Asset::class, 'imageable')->where('type', 'SCANNER');;
    }

    public function is_active(){
        return $this->status == 'ACTIVE';
    }

    public function medical_histories()
    {
        return $this->hasMany(MedicalHistory::class, 'clinic_id');
    }
}
