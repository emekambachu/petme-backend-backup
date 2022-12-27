<?php

namespace App\Http\Resources\Appointment\Service;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentServiceResource extends JsonResource
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
          'service' => $this->service ? $this->service->name : null,
          'cost' => $this->service ? $this->service->cost : null,
        ];
    }

}
