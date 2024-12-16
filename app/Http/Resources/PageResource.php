<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;
use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Support\Str;
use stdClass;

class PageResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $sections = new stdClass;
        if (isset($this->sections)) {
            $sectionKeys = new PageSectionCollection($this->sections);

            $sections = array();
            foreach ($sectionKeys as $section) {
                $sections[$section->section_name] = new stdClass;

                foreach ($section->contents as $content) {
                    if ($section->section_name == "BANNER_IMAGE") {
                        $mobile = $content->images->where("viewport", Media::MOBILE)->first();
                        $desktop = $content->images->where("viewport", Media::DESKTOP)->first();

                        $sections[$section->section_name]->{$content->group->name . "_" . $content->seq} = [
                            "MOBILE" => (new MediaStorage())->getFile($mobile->id, $mobile->media_name),
                            "DESKTOP" => (new MediaStorage())->getFile($desktop->id, $desktop->media_name),
                        ];
                    } else {
                        $sections[$section->section_name]->{$content->group->name . "_" . $content->seq} = [
                            "key" => $content->key,
                            "value" => $content->value,
                        ];
                    }
                }
            }
        }

        return [
            'id' => $this->id,
            'page_name' => $this->page_name,
            'sections' => $sections
        ];
    }
}
