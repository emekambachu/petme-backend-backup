<?php

namespace App\Http\Resources\User\Appointment;

use App\Http\Resources\Appointment\Service\AppointmentServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'reference' => $this->reference,
            'id' => $this->id,
            'services' => $this->appointment_services ? AppointmentServiceResource::collection($this->appointment_services) : null,
            'note' => $this->note,
            'total_cost' => $this->total_cost,
            'appointment_time' => $this->appointment_time,
            'appointment_type' => $this->appointment_type ? $this->appointment_type->name : null,
            'created_at' => $this->created_at,

            'service_provider_name' => $this->service_provider ? $this->service_provider->name : null,
            'service_provider_email' => $this->service_provider ? $this->service_provider->email : null,
            'service_provider_mobile' => $this->service_provider ? $this->service_provider->mobile : null,
            'service_provider_location' => $this->service_provider ? $this->service_provider->location : null,
            'service_provider_specialization' => $this->service_provider ? $this->service_provider->specialization : null,

            'pet_type' => $this->pet && $this->pet->type ? $this->pet->type->name : null,

            'status' => $this->status === 1 ? 'accepted' : 'pending',
            'user_completed' => $this->user_completed === 1 ? 'completed' : 'pending',
            'service_provider_completed' => $this->service_provider_completed === 1 ? 'completed' : 'pending',
        ];
    }
}
