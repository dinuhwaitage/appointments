<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'status','clinic_id','contact_id','date_of_birth'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function appoitments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
}
