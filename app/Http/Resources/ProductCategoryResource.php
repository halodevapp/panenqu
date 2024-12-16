<?php

namespace App\Http\Resources;

class ProductCategoryResource extends BaseResource
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
            'category_slug' => $this->category_slug,
            'category_name' => $this->category_name,
            'category_description' => $this->category_description,
            'images' => $this->when($this->relationLoaded('images'), function () {
                if ($this->images->count() == 1) {
                    return new MediaResource($this->images[0]);
                } else {
                    return new MediaCollection($this->images);
                }
            }),
            'products' => (new ProductCollection($this->whenLoaded('products')))
        ];
    }
}
