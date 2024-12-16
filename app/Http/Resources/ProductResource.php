<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;

class ProductResource extends BaseResource
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
            'product_slug' => $this->product_slug,
            'product_name' => $this->product_name,
            'product_description' => $this->product_description,
            'product_category' => new ProductCategoryResource($this->whenLoaded('category')),
            'images' => new MediaCollection($this->whenLoaded('images')),
            'stores' => new ProductHasStoreCollection($this->whenLoaded('stores')),
            'related' => new ProductCollection($this->whenLoaded('related')),
        ];
    }
}
