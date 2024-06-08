<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    
    protected $fillable = ['line1', 'line2','city', 'state','zipcode','status','clinic_id'];

    public function addressable()
    {
        return $this->morphTo();
    }
}
