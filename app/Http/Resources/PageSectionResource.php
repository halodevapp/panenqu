<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PageSectionResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $sections = [
            'section_name' => $this->section_name
        ];

        $sectionKeys = collect($sections)->flip()->keys()->first();

        return $sectionKeys;
    }
}
