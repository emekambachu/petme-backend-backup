<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopItemResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'cost' => $this->cost,
            'metric' => $this->metric,
            'images' => $this->images,
            'category' => $this->category,
            'created_at' => $this->created_at,
        ];
    }
}
