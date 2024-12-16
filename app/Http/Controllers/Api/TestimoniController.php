<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TestimoniException;
use App\Http\Controllers\Controller;
use App\Http\Resources\TestimoniCollection;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestimoniController extends Controller
{
    public function __invoke()
    {
        try {
            $testimoni = Testimoni::orderBy('id', 'desc')->get();
            return new TestimoniCollection($testimoni);
        } catch (\Throwable $th) {
            report($th);
            throw new TestimoniException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
