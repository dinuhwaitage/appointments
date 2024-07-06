<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'date_of_birth','date_of_join', 'designation','qualification','status','clinic_id','contact_id'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
        //return $this->morphOne(Contact::class, 'contactable');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
