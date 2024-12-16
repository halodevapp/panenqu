<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;

class StoreResource extends BaseResource
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
            'store_name' => $this->store_name,
            'store_location' => $this->store_location,
            'store_phone' => $this->store_phone,
            'store_link' => $this->store_link,
            'store_latitude' => $this->store_latitude,
            'store_longitude' => $this->store_longitude,
            'images' => $this->when($this->relationLoaded('storeImageOnline'), function () {
                if ($this->storeImageOnline->count() == 1) {
                    return new MediaResource($this->storeImageOnline[0]);
                } else {
                    return new MediaCollection($this->storeImageOnline);
                }
            }),
        ];
    }
}
