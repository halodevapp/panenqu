<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\MitraException;
use App\Http\Controllers\Controller;
use App\Http\Resources\MitraFaqCollection;
use App\Models\MitraFaq;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MitraFaqController extends Controller
{
    public function __invoke()
    {
        try {
            $mitraFAQ = MitraFaq::all();
            return new MitraFaqCollection($mitraFAQ);
        } catch (\Throwable $th) {
            report($th);
            throw new MitraException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
