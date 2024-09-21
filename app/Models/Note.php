<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $fillable = ['name','clinic_id','status'];

    
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
