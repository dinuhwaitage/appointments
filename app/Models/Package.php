<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $fillable = ['name','amount','description','clinic_id','status','seating_count'];

    
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'package_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
