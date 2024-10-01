<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function serialize_permission(){
        $arr =[];
        $permissions = $this->permissions();
        foreach($permissions as $key => $permission){
            foreach($permissions[$key] as $val){
                array_push($arr, $val."".$key);
            }
        }
        return $arr;
    }

    public function permissions()
    {
        if ($this->name == 'STAFF'){
           return [
                'Patient'=>["list","read","add","edit","delete"],
                "Appointment"=>["list","read","add","edit","delete"],
                "Invoice"=>["list","read","add","edit","delete"],
                "Prescription"=>["list","read"]
           ];
        }elseif($this->name == 'DOCTOR'){
            return [
                "Appointment"=>["list","read","edit"],
                "Patient"=>["list","read"],
                "Note"=>["list","read","add","edit","delete"],
                "Medicine"=>["list","read","add","edit","delete"],
                "Prescription"=>["list","read","add","edit","delete"]
            ];
        }elseif($this->name == 'ADMIN'){
            return [
                "Patient"=>["list","read","add","edit",'delete'],
                "Appointment"=>["list","read","add","edit","delete"],
                "Invoice"=>["list","read","add","edit","delete"],
                "Package"=>["list","read","add","edit","delete"],
                "Employee"=>["list","read","add","edit","delete"],
                "Doctor"=>["list","read","add","edit","delete"],
                "Note"=>["list","read","add","edit","delete"],
                "Medicine"=>["list","read","add","edit","delete"],
                "Prescription"=>["list","read","add","edit","delete"],
                "Report"=>["list","read"],
                "Clinic"=>["dashboard","read","edit"]
            ];
        }elseif($this->name == 'ROOT'){
            return [
                "Patient"=>["list","read","add","edit",'delete'],
                "Appointment"=>["list","read","add","edit","delete"],
                "Invoice"=>["list","read","add","edit","delete"],
                "Package"=>["list","read","add","edit","delete"],
                "Employee"=>["list","read","add","edit","delete"],
                "Doctor"=>["list","read","add","edit","delete"],
                "Note"=>["list","read","add","edit","delete"],
                "Medicine"=>["list","read","add","edit","delete"],
                "Prescription"=>["list","read","add","edit","delete"],
                "Report"=>["list","read"],
                "Clinic"=>["dashboard","read","edit"]
            ];
        }

    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_roles');
    }
}
