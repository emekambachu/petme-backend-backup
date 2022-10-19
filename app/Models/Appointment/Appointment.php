<?php

namespace App\Models\Appointment;

use App\Models\Pet\Pet;
use App\Models\ServiceProvider\ServiceProviderModel;
use App\Models\ServiceProvider\ServiceProviderCategory;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pet_id',
        'service_provider_id',
        'service_provider_category_id',
        'appointment_type_id',
        'note',
        'location',
        'appointment_time',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pet(){
        return $this->belongsTo(Pet::class, 'pet_id', 'id');
    }

    public function service_provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderModel::class, 'service_provider_id', 'id');
    }

    public function service_provider_category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderCategory::class,
            'service_provider_category_id', 'id');
    }

    public function appointment_type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AppointmentType::class,
            'appointment_type_id', 'id');
    }
}
