<?php

namespace App\Http\Resources\Appointment\Service;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AppointmentServiceCollection extends ResourceCollection
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
            'data' => AppointmentServiceResource::collection($this->collection),
            'meta' => [
                'total' => $this->collection->count()
            ]
        ];
    }
}
