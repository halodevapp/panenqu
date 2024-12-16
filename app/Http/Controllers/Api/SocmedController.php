<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InstagramMediaException;
use App\Http\Controllers\Controller;
use App\Http\Resources\InstagramMediaCollection;
use App\Models\InstagramMedia;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SocmedController extends Controller
{
    public function instagramMedia()
    {
        try {
            $instagram = InstagramMedia::all();
            return new InstagramMediaCollection($instagram);
        } catch (\Throwable $th) {
            report($th);
            throw new InstagramMediaException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
