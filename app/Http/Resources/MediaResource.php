<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;

class MediaResource extends BaseResource
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
            'category' => $this->model_category,
            'viewport' => $this->viewport,
            'media_type' => $this->media_type,
            'url' => (new MediaStorage())->getFile($this->id, $this->media_name)
        ];
    }
}
