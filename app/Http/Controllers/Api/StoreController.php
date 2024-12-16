<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\StoreException;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreCollection;
use App\Models\Store;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public function byCategory($category)
    {
        try {
            $customers = Store::with('storeImageOnline')->orderBy('id', 'desc')->whereHas('StoreType', function ($query) use ($category) {
                $query->where('type_name', $category);
            })->get();
            return new StoreCollection($customers);
        } catch (\Throwable $th) {
            report($th);
            throw new StoreException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
