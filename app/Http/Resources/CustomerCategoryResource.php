<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;

class CustomerCategoryResource extends BaseResource
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
            'category_name' => $this->category_name,
            'customer' => new CustomerCollection($this->whenLoaded('customer'))
        ];
    }
}
