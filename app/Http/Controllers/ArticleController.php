<?php

namespace App\Http\Controllers;

use App\Events\ArticleCreated;
use App\Helpers\MediaStorage;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Media;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articles = Article::where(function ($query) use ($request) {
            $query->where('article_title', 'like', "%{$request->search}%")
                ->orWhereHas('category', function ($query) use ($request) {
                    $query->where('category_name', 'like', "%{$request->search}%");
                });
        })->orderBy('id', 'DESC')->paginate(10);
        return view('article.article_index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ArticleCategory::all();
        return view('article.article_create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $article = Article::create([
                'article_title' => $request->article_title,
                'article_category' => $request->article_category,
                'article_content' => $request->article_content,
                'publish_date' => $request->submit == 'publish' ? Carbon::now() : null,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            /**
             * Image Thumbnail
             */
            $thumbnailSeq = 1;
            foreach ($request->article_image_thumbnail as $key => $image) {
                $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::ARTICLE_PATH . "/" . $article->id;
                $fileName = $article->article_slug . "-thumbnail-" . $thumbnailSeq . "." . $image->extension();
                $mimeType = $image->getClientMimeType();

                $image->storeAs($storagePath, $fileName);

                $filePath = $storagePath . "/" . $fileName;
                if (Storage::exists($filePath)) {
                    Media::create([
                        'model_category' => Article::MEDIA_CATEGORY_THUMBNAIL,
                        'model_id' =>  $article->id,
                        'viewport' => Media::DESKTOP,
                        'media_type' => $mimeType,
                        'media_name' => $fileName,
                        'path' => $filePath,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ]);
                }

                $thumbnailSeq++;
            }

            /**
             * Image Banner
             */
            $bannerSeq = 1;
            foreach ($request->article_image_banner as $key => $image) {
                $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::ARTICLE_PATH . "/" . $article->id;
                $fileName = $article->article_slug . "-banner-" . $bannerSeq . "." . $image->extension();
                $mimeType = $image->getClientMimeType();

                $image->storeAs($storagePath, $fileName);

                $filePath = $storagePath . "/" . $fileName;
                if (Storage::exists($filePath)) {
                    Media::create([
                        'model_category' => Article::MEDIA_CATEGORY_BANNER,
                        'model_id' =>  $article->id,
                        'viewport' => Media::DESKTOP,
                        'media_type' => $mimeType,
                        'media_name' => $fileName,
                        'path' => $filePath,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ]);
                }
                $bannerSeq++;
            }


            DB::commit();

            if ($request->submit == 'publish') {
                $article->load('banner');
                ArticleCreated::dispatch($article);
            }

            return redirect(route("article.index"))
                ->with("response-message", "Create product berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("article.index"))
                ->with("response-message", "Create product gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        $categories = ArticleCategory::all();

        $article->load(['thumbnail', 'banner']);
        foreach ($article->thumbnail as $key => $image) {
            $article->thumbnail[$key]->url = (new MediaStorage())->getFile($image->id, $image->media_name);
        }

        foreach ($article->banner as $key => $image) {
            $article->banner[$key]->url = (new MediaStorage())->getFile($image->id, $image->media_name);
        }

        return view('article.article_edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleUpdateRequest $request, Article $article)
    {
        DB::beginTransaction();
        try {

            $article->article_title = $request->article_title;
            $article->article_category = $request->article_category;
            $article->article_content = $request->article_content;
            $article->publish_date = $request->submit == 'publish' ? Carbon::now() : null;
            $article->updated_by = Auth::user()->id;
            $article->save();

            $article->load(['thumbnail', 'banner']);

            /**
             * Jika title article berubah
             */
            if ($article->wasChanged('article_title')) {
                /**
                 * Update nama file sesuai article title yang baru
                 */
                $oldThumbnailSeq = 1;
                foreach ($article->thumbnail as $key => $image) {

                    $explodedName = explode(".", $image->media_name);
                    $extension = end($explodedName);

                    $newName = $article->article_slug . "-thumbnail-" . $oldThumbnailSeq . "." . $extension;
                    $newPath = Media::ARTICLE_PATH . "/" . $article->id . "/" . $newName;
                    if (Storage::exists($image->path)) {
                        Storage::move($image->path, $newPath);
                        $article->thumbnail[$key]->media_name = $newName;
                        $article->thumbnail[$key]->path = $newPath;
                        $article->thumbnail[$key]->save();
                    }

                    $oldThumbnailSeq++;
                }

                $oldBannerSeq = 1;
                foreach ($article->banner as $key => $image) {

                    $explodedName = explode(".", $image->media_name);
                    $extension = end($explodedName);

                    $newName = $article->article_slug . "-banner-" . $oldBannerSeq . "." . $extension;
                    $newPath = Media::ARTICLE_PATH . "/" . $article->id . "/" . $newName;

                    if (Storage::exists($image->path)) {
                        Storage::move($image->path, $newPath);

                        $article->banner[$key]->media_name = $newName;
                        $article->banner[$key]->path = $newPath;
                        $article->banner[$key]->save();
                    }

                    $oldBannerSeq++;
                }
            }

            $article->unsetRelation('thumbnail');
            $article->unsetRelation('banner');

            $newThumbnailSeq = $article->thumbnail()->withTrashed()->count();
            if ($request->hasFile('article_image_thumbnail')) {
                /**
                 * Flag delete file yang lama
                 */
                $article->thumbnail()->update([
                    'deleted_at' => Carbon::now(),
                    'deleted_by' => Auth::user()->id
                ]);

                foreach ($request->article_image_thumbnail as $key => $image) {
                    $newThumbnailSeq++;

                    $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::ARTICLE_PATH . "/" . $article->id;
                    $fileName = $article->article_slug . "-thumbnail-" . $newThumbnailSeq . "." . $image->extension();
                    $mimeType = $image->getClientMimeType();

                    $image->storeAs($storagePath, $fileName);

                    $filePath = $storagePath . "/" . $fileName;
                    if (Storage::exists($filePath)) {
                        Media::create([
                            'model_category' => Article::MEDIA_CATEGORY_THUMBNAIL,
                            'model_id' =>  $article->id,
                            'viewport' => Media::DESKTOP,
                            'media_type' => $mimeType,
                            'media_name' => $fileName,
                            'path' => $filePath,
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ]);
                    }
                }
            }

            $newBannerSeq = $article->banner()->withTrashed()->count();
            if ($request->hasFile('article_image_banner')) {
                /**
                 * Flag delete file yang lama
                 */
                $article->banner()->update([
                    'deleted_at' => Carbon::now(),
                    'deleted_by' => Auth::user()->id
                ]);


                foreach ($request->article_image_banner as $key => $image) {
                    $newBannerSeq++;

                    $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::ARTICLE_PATH . "/" . $article->id;
                    $fileName = $article->article_slug . "-banner-" . $newBannerSeq . "." . $image->extension();
                    $mimeType = $image->getClientMimeType();

                    $image->storeAs($storagePath, $fileName);

                    $filePath = $storagePath . "/" . $fileName;
                    if (Storage::exists($filePath)) {
                        Media::create([
                            'model_category' => Article::MEDIA_CATEGORY_BANNER,
                            'model_id' =>  $article->id,
                            'viewport' => Media::DESKTOP,
                            'media_type' => $mimeType,
                            'media_name' => $fileName,
                            'path' => $filePath,
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ]);
                    }
                }
            }

            DB::commit();
            if ($request->submit == 'publish') {
                $article->unsetRelation('banner');
                $article->load('banner');
                ArticleCreated::dispatch($article);
            }

            return redirect(route("article.index"))
                ->with("response-message", "Update article berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("article.index"))
                ->with("response-message", "Update article gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        DB::beginTransaction();
        try {

            $article->deleted_at = Carbon::now();
            $article->deleted_by = Auth::user()->id;
            $article->save();

            $article->thumbnail()->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->id
            ]);

            $article->banner()->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->id
            ]);

            DB::commit();

            return redirect(route("article.index"))
                ->with("response-message", "Delete article berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("article.index"))
                ->with("response-message", "Delete article gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function unpublish(Request $request)
    {
        $this->authorize('unpublish', Article::class);

        DB::beginTransaction();
        try {
            $article = Article::find($request->id);

            $article->publish_date = null;
            $article->updated_by = Auth::user()->id;
            $article->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Unpublish success'
            ]);
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Unpublish failed'
            ]);
        }
        DB::rollBack();
    }
}
