<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PageContentResource extends BaseResource
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
            'id' => $this->id,
            'page_section' => $this->page_section,
            'section_name' => $this->section->section_name,
            'page_type' => $this->page_type,
            'type_name' => $this->group->name,
            'seq' => $this->seq,
            'key' => $this->key,
            'value' => $this->value,
        ];
    }
}
