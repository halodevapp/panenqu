<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;

class MitraFaqResource extends BaseResource
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
            'question' => $this->question,
            'answer' => $this->answer
        ];
    }
}
