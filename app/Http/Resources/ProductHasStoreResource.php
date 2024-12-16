<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;

class ProductHasStoreResource extends BaseResource
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
            'store_name' => $this->store->store_name,
            'store_link' => $this->product_store_link,
            'images' => $this->when($this->relationLoaded('store'), function () {
                if ($this->store->storeImageOnline->count() == 1) {
                    return new MediaResource($this->store->storeImageOnline[0]);
                } else {
                    return new MediaCollection($this->store->storeImageOnline);
                }
            }),
        ];
    }
}
