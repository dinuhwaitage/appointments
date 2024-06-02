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

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}
