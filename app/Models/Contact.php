<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['first_name','last_name','email','mobile','status'];

    public function contactable()
    {
        return $this->morphTo();
    }

    public function patient()
    {
        return $this->hasOne(Patient::class, 'contact_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'contact_id');
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'contact_roles');
    }

    public function role_permissions()
    { $arr = [];
        foreach($this->roles as $role){
            $arr = array_merge($arr, $role->serialize_permission());
        }
        return collect($arr);
    }

    public function firstRole()
    {
        return optional(optional($this->roles())->first())->name;
    }
}
