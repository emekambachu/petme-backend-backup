<?php

namespace App\Models\Appointment;

use App\Models\ServiceProvider\ServiceProviderModel;
use App\Models\ServiceProvider\ServiceProviderService;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentService extends Model
{
    use HasFactory;
    protected $fillable = [
      'appointment_id',
      'user_id',
      'service_provider_id',
      'service_provider_service_id',
    ];

    public function appointment(){
        return $this->belongsTo(Appointment::class, 'appointment_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function service_provider(){
        return $this->belongsTo(ServiceProviderModel::class, 'service_provider_id', 'id');
    }

    public function service(){
        return $this->belongsTo(ServiceProviderService::class, 'service_provider_service_id', 'id');
    }
}
