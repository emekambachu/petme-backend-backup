<?php

namespace App\Http\Resources\ServiceProvider\Appointment;

use App\Http\Resources\Appointment\Service\AppointmentServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceProviderAppointmentResource extends JsonResource
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
            'id' => $this->id,
            'services' => $this->appointment_services ? AppointmentServiceResource::collection($this->appointment_services) : null,
            'note' => $this->note,
            'total_cost' => $this->total_cost,
            'appointment_time' => $this->appointment_time,
            'appointment_type' => $this->appointment_type ? $this->appointment_type->name : null,
            'created_at' => $this->created_at,

            'pet_owner' => $this->user ? $this->user->name : null,
            'pet_owner_email' => $this->user ? $this->user->email : null,
            'pet_owner_address' => $this->user ? $this->user->address : null,
            'pet_owner_location' => $this->user && $this->user->location ? $this->user->location->country_name : null,

            'pet_type' => $this->pet && $this->pet->type ? $this->pet->type->name : null,

            'status' => $this->status === 1 ? 'accepted' : ($this->status === 2 ? 'declined' : 'pending'),
            'user_completed' => $this->user_completed === 1 ? 'completed' : 'pending',
            'service_provider_completed' => $this->service_provider_completed === 1 ? 'completed' : 'pending',
        ];
    }
}
