<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;

class TestimoniResource extends BaseResource
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
            'name' => $this->name,
            'profession' => $this->profession,
            'rating' => $this->rating,
            'testimoni' => $this->testimoni
        ];
    }
}
