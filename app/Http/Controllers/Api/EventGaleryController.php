<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\EventException;
use App\Http\Controllers\Controller;
use App\Http\Resources\MediaCollection;
use App\Models\Media;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EventGaleryController extends Controller
{
    public function __invoke()
    {
        try {
            $galery = Media::where('model_category', Media::EVENT_GALERY_MODEL)->get();
            return new MediaCollection($galery);
        } catch (\Throwable $th) {
            report($th);
            throw new EventException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
