<?php

namespace App\Models\User;

use App\Models\ServiceProvider\AppointmentType;
use App\Models\ServiceProvider\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAppointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pet_id',
        'service_provider_id',
        'appointment_type_id',
        'type',
        'note',
        'location',
        'appointment_time',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pet(){
        return $this->belongsTo(User::class, 'pet_id', 'id');
    }

    public function service_provider(){
        return $this->belongsTo(ServiceProvider::class, 'service_provider_id', 'id');
    }

    public function appointment_type(){
        return $this->belongsTo(AppointmentType::class, 'appointment_type_id', 'id');
    }
}
