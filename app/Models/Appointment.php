<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = ['details','date','time','patient_id','doctor_id', 'status','clinic_id','diagnosis','fee','package_id','doctor_note','weight','height','seating_no','bp_detail','medical_history','family_medical_history','current_condition','observation_details','investigation_details','treatment_plan','procedures'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Employee::class, 'doctor_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'imageable');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'appointment_id');
    }

    public function additional_fees()
    {
        return $this->morphMany(AdditionalFee::class, 'additionable_fee');
    }

}
