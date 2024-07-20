<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function permissions()
    {
        if ($this->name == 'STAFF'){
           return [
                'Patient'=>["list","read","add","edit","delete"]
           ];
        }elseif($this->name == 'DOCTOR'){
            return [
                "Patient"=>["list","read","add","edit","delete"],
                "Appointment"=>["list","read","add","edit","delete"]
            ];
        }elseif($this->name == 'ADMIN'){
            return [
                "Patient"=>["list","read","add","edit",'delete'],
                "Appointment"=>["list","read","add","edit","delete"],
                "Invoice"=>["list","read","add","edit","delete"],
                "Package"=>["list","read","add","edit","delete"],
                "Employee"=>["list","read","add","edit","delete"],
                "Doctor"=>["list","read","add","edit","delete"]
            ];
        }

    }
}
