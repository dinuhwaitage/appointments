<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $fillable = ['name','amount','description','clinic_id','status'];

    
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
