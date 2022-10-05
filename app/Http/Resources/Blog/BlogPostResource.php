<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'author' => $this->author,
            'description' => $this->description,
            'image' => $this->image,
            'image_path' => $this->image_path,
            'category' => $this->category,
            'comments' => $this->comments,
            'likes' => count($this->likes),
            'views' => count($this->views),
        ];
    }
}
