<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'status','clinic_id','contact_id','date_of_birth','gender','package_id','registration_date','package_start_date','number','package_end_date','abha_number','available_count'];

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

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'patient_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function is_expiring_soon()
    {

        if($this->package_end_date){

            $datetime1 = new DateTime($this->package_end_date);
            $datetime2 = new DateTime();
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');//now do whatever you like with $days
            return $days < 3;

        }else{
            return false;
        }
        
    }

    public function package_appointments($package_id)
    {
        $pkg_id = $package_id ? $package_id : $this->package_id;
        return $this->appoitments->where('package_id', $pkg_id)->where('status','<>', 'CANCLED');
    }

    public function available_package_count($package_id = null)
    {
        return count($this->package_appointments($package_id)) ? optional($this->package)->seating_count - count($this->package_appointments($package_id)) : optional($this->package)->seating_count;
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'imageable');
    }

    public function medical_histories()
    {
        return $this->hasMany(MedicalHistory::class, 'patient_id');
    }
}
