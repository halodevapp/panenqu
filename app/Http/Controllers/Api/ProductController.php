<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ProductException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductSortRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function productByCategory(ProductSortRequest $request, $slug)
    {
        try {
            $per_page = 8;

            if (filter_var($request->per_page, FILTER_VALIDATE_INT) !== FALSE) {
                if (abs($request->per_page) > 100) {
                    $per_page = 100;
                } else {
                    $per_page = abs($request->per_page);
                }
            }

            $allowed_sort = [
                "product_name",
                "product_slug"
            ];

            $orderBy = $request->has("order") ?  array_keys($request->order) : [];

            $products = Product::with(['images'])->where(function ($query) use ($slug) {
                $query->whereHas('category', function ($query) use ($slug) {
                    $query->where('category_slug', $slug);
                });
            })
                ->orderByRaw("ISNULL(sequence) asc")
                ->orderBy("sequence", "asc")
                ->when(!empty($orderBy), function ($query) use ($request, $orderBy, $allowed_sort) {
                    foreach ($orderBy as $order) {
                        if (in_array($order, $allowed_sort)) {
                            $request->order[$order] != "" ? $query->orderBy($order, $request->order[$order]) : null;
                        }
                    }
                })
                ->orderBy("id", "desc")
                ->paginate($per_page);

            return new ProductCollection($products);
        } catch (\Throwable $th) {
            report($th);
            throw new ProductException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function productBySlug(Request $request, $slug)
    {
        try {
            $product = Product::with(['images', 'stores', 'related' => function ($query) use ($slug) {
                return $query->with('images')->where('product_slug', '!=', $slug);
            }])->where('product_slug', $slug)->first();

            if (!$product) {
                throw new ProductException("Produk tidak ditemukan", Response::HTTP_NOT_FOUND);
            }

            return new ProductResource($product);
        } catch (\Throwable $th) {
            report($th);
            throw new ProductException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
