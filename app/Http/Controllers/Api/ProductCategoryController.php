<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ProductCategoryException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryCollection;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductCategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = ProductCategory::with('images')->get();
            return new ProductCategoryCollection($categories);
        } catch (\Throwable $th) {
            report($th);
            throw new ProductCategoryException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
