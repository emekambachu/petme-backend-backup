<?php

namespace App\Models\Appointment;

use App\Models\ServiceProvider\ServiceProviderModel;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id',
        'user_id',
        'service_provider_id',
        'amount',
        'status',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function service_provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderModel::class, 'service_provider_id', 'id');
    }

    public function appointment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'id');
    }

}
