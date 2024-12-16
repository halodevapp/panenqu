<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ArticleException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        try {
            $per_page = 10;

            if (filter_var($request->per_page, FILTER_VALIDATE_INT) !== FALSE) {
                if (abs($request->per_page) > 100) {
                    $per_page = 100;
                } else {
                    $per_page = abs($request->per_page);
                }
            }

            $articles = Article::with(['thumbnail', 'banner'])
                ->published()
                ->orderBy("created_at", "desc")
                ->paginate($per_page);

            return new ArticleCollection($articles);
        } catch (\Throwable $th) {
            report($th);
            throw new ArticleException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function articleBySlug($slug)
    {
        try {
            $article = Article::with(['thumbnail', 'banner', 'related' => function ($query) use ($slug) {
                return $query->with('thumbnail')->published()->where('article_slug', '!=', $slug);
            }])->published()->where('article_slug', $slug)->first();

            if (!$article) {
                throw new ArticleException("Artikel tidak ditemukan", Response::HTTP_NOT_FOUND);
            }

            $article->loadOther = true;
            $article->other = Article::published()
                ->where('id', '!=', $article->id)
                ->whereBetween('publish_date', [
                    now()->subDay('30'),
                    now()
                ])->inRandomOrder()->limit(4)->get();

            return new ArticleResource($article);
        } catch (\Throwable $th) {
            report($th);
            throw new ArticleException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function articleByCategory(Request $request, $slug)
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

            $products = Article::with(['thumbnail', 'banner'])->where(function ($query) use ($slug) {
                $query->whereHas('category', function ($query) use ($slug) {
                    $query->where('category_slug', $slug);
                });
            })
                ->orderBy("created_at", "desc")
                ->paginate($per_page);

            return new ArticleCollection($products);
        } catch (\Throwable $th) {
            report($th);
            throw new ArticleException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
