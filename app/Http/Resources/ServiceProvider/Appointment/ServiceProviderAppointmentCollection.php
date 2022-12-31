<?php

namespace App\Http\Resources\ServiceProvider\Appointment;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceProviderAppointmentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => ServiceProviderAppointmentResource::collection($this->collection),
            'meta' => [
                'total' => $this->collection->count()
            ]
        ];
    }
}
