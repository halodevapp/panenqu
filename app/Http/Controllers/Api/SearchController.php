<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchCollection;
use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
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

            $products = Product::with(['images'])->where(function ($query) use ($request) {
                $query->where('product_name', 'like', "%{$request->q}%")
                    ->orWhere('product_slug', 'like', "%{$request->q}%")
                    ->orWhere('product_description', 'like', "%{$request->q}%")
                    ->orWhere(function ($query) use ($request) {
                        $query->whereHas('category', function ($query) use ($request) {
                            $query->where('category_slug', 'like', "%{$request->q}%")
                                ->orWhere('category_name', 'like', "%{$request->q}%");
                        });
                    });
            })->paginate($per_page);

            $articles = Article::with(['thumbnail', 'banner'])
                ->published()
                ->where(function ($query) use ($request) {
                    $query->where('article_slug', 'like', "%{$request->q}%")
                        ->orWhere('article_title', 'like', "%{$request->q}%")
                        ->orWhere('article_content', 'like', "%{$request->q}%")
                        ->orWhere(function ($query) use ($request) {
                            $query->whereHas('category', function ($query) use ($request) {
                                $query->where('category_slug', 'like', "%{$request->q}%")
                                    ->orWhere('category_name', 'like', "%{$request->q}%");
                            });
                        });
                })
                ->orderBy('publish_date', 'desc')
                ->paginate($per_page);

            $data = array();

            foreach ($articles as $article) {
                array_push($data, [
                    'title' => $article->article_title,
                    'slug' => $article->article_slug,
                    'category' => $article->category->category_name,
                    'category_slug' => $article->category->category_slug,
                    'description' => $article->article_content,
                    'images' => $article->thumbnail,
                    'type' => 'ARTICLE',
                    'link' => route('api.article.articleBySlug', ['slug' => $article->article_slug])
                ]);
            }

            foreach ($products as $product) {
                array_push($data, [
                    'title' => $product->product_name,
                    'slug' => $product->product_slug,
                    'category' => $product->category->category_name,
                    'category_slug' => $product->category->category_slug,
                    'description' => $product->product_description,
                    'images' => $product->images,
                    'type' => 'PRODUCT',
                    'link' => route('api.product.productBySlug', ['slug' => $product->product_slug])

                ]);
            }

            return new SearchCollection($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
