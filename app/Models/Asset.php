<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'caption','clinic_id'];

    public function imageable()
    {
        return $this->morphTo();
    }
}
