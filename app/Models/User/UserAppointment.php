<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAppointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pet_id',
        'type',
        'description',
        'date_booked',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pet(){
        return $this->belongsTo(User::class, 'pet_id', 'id');
    }
}
