<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['clinic_id', 'plan_id', 'start_date', 'end_date', 'status','description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
