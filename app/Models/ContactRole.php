<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactRole extends Model
{
    protected $fillable = ['contact_id', 'role_id'];
    use HasFactory;
    

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

}
