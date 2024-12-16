<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleCategoryStoreRequest;
use App\Http\Requests\ArticleCategoryUpdateRequest;
use App\Models\ArticleCategory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleCategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ArticleCategory::class, 'article_category');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articleCategories = ArticleCategory::where(function ($query) use ($request) {
            $query->where('category_name', 'like', "%{$request->search}%");
        })->orderBy('id', 'desc')->paginate(10);
        return view('article_category.article_category_index', compact('articleCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('article_category.article_category_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleCategoryStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            ArticleCategory::create([
                'category_name' => $request->category_name,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            DB::commit();

            return redirect(route("article-category.index"))
                ->with("response-message", "Create article category berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("article-category.index"))
                ->with("response-message", "Create article category gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ArticleCategory  $articleCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ArticleCategory $articleCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ArticleCategory  $articleCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ArticleCategory $articleCategory)
    {
        return view('article_category.article_category_edit', compact('articleCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ArticleCategory  $articleCategory
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleCategoryUpdateRequest $request, ArticleCategory $articleCategory)
    {
        DB::beginTransaction();
        try {

            $articleCategory->category_name = $request->category_name;
            $articleCategory->updated_by = Auth::user()->id;
            $articleCategory->save();

            DB::commit();

            return redirect(route("article-category.index"))
                ->with("response-message", "Update article category berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("article-category.index"))
                ->with("response-message", "Update article category gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ArticleCategory  $articleCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArticleCategory $articleCategory)
    {
        DB::beginTransaction();
        try {
            $articleCategory->deleted_at = Carbon::now();
            $articleCategory->deleted_by = Auth::user()->id;
            $articleCategory->save();

            DB::commit();

            return redirect(route("article-category.index"))
                ->with("response-message", "Delete article category berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("article-category.index"))
                ->with("response-message", "Delete article category gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
