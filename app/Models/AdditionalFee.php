<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalFee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'amount','clinic_id','description'];

    public function additionable_fee()
    {
        return $this->morphTo();
    }
}
