<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $fillable = ['name','clinic_id','status','type'];

    
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
