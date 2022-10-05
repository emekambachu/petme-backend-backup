<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogPostCollection extends ResourceCollection
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
            'data' => BlogPostResource::collection($this->collection)
        ];
    }
}
