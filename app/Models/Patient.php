<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'status','clinic_id'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function contact()
    {
        return $this->morphOne(Contact::class, 'contactable');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
