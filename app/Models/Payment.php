<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $casts = [
        'response' => 'array', // Laravel automatically converts JSON to array
    ];

    protected $fillable = ['user_id', 'clinic_id', 'subscription_id', 'amount', 'status', 'transaction_id','payment_date','description','response'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
