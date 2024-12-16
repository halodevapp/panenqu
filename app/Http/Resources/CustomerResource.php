<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;

class CustomerResource extends BaseResource
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
            'customer_name' => $this->customer_name,
            'images' => $this->when($this->relationLoaded('images'), function () {
                if ($this->images->count() == 1) {
                    return new MediaResource($this->images[0]);
                } else {
                    return new MediaCollection($this->images);
                }
            }),
        ];
    }
}
