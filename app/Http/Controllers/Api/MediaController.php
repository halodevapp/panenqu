<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function show($id)
    {
        $media = Media::findOrFail($id);

        $image = Http::get(env('AWS_MEDIA_PUBLIC') . $media->path);
        header('Content-type: ' . $media->media_type);
        echo $image;
    }
}
