<?php

namespace App\Http\Resources;

class PopupResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'web_link' => $this->web_link,
            'image' => new MediaResource($this->whenLoaded('image'))
        ];
    }
}
