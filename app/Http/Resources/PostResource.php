<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'title'         => $this->title,
            'slug'          => $this->slug,
            'body'          => $this->body,
            'author'        => User::collection($this->whenLoaded('user')),
            'category'      => Category::collection($this->wheLoaded('category')),
            'is_published'  => $this->is_published,
            'published_at'  => $this->published_at,
            'tagged'        => $this->tagged
        ];
    }
}
