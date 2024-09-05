<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'caption','clinic_id','mime_type','file_name', 'file_size'];

    public function imageable()
    {
        return $this->morphTo();
    }
}
