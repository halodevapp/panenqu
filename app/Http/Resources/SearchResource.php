<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;

class SearchResource extends BaseResource
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
            'title' => $this['title'],
            'slug' => $this['slug'],
            'category' => $this['category'],
            'category_slug' => $this['category_slug'],
            'description' => $this['description'],
            'images' => new MediaCollection($this['images']),
            'type' => $this['type'],
            'link' => $this['link'],
        ];
    }
}
