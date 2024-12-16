<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;

class InstagramMediaResource extends BaseResource
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
            'media_type' => $this->media_type,
            'media_url' => $this->media_url,
            'permalink' => $this->permalink,
            'thumbnail_url' => $this->thumbnail_url,
            'timestamp' => $this->timestamp,
            'username' => $this->username,
            'caption' => $this->caption,
            'id' => $this->id
        ];
    }
}
