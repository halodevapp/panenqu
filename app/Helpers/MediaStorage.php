<?php

namespace App\Helpers;

use App\Models\Media;

class MediaStorage
{
    public $url;

    public function __construct($type = null)
    {
        $this->url = env('APP_URL') . "/api/media/";
    }

    public function getFile($id, $media_name)
    {
        $image = Media::findOrFail($id);
        return $this->url . $image->id . "/" . $image->media_name;
    }
}
