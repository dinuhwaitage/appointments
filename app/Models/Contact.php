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
}
