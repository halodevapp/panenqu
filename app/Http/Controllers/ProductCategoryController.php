<?php

namespace App\Http\Controllers;

use App\Helpers\MediaStorage;
use App\Http\Requests\ProductCategoryStoreRequest;
use App\Http\Requests\ProductCategoryUpdateRequest;
use App\Models\Media;
use App\Models\ProductCategory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(ProductCategory::class, 'product_category');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productCategories = ProductCategory::where(function ($query) use ($request) {
            $query->where('category_name', 'like', "%{$request->search}%")
                ->orWhere('category_description', 'like', "%{$request->search}%");
        })->orderBy('id', 'DESC')->paginate(10);
        return view('product_category.product_category_index', compact('productCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product_category.product_category_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCategoryStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $productCategory = ProductCategory::create([
                'category_name' => $request->category_name,
                'category_description' => $request->category_description,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            $imageSeq = 1;
            foreach ($request->category_image as $key => $image) {
                $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::PRODUCT_CATEGORY_PATH . "/" . $productCategory->id;
                $fileName = $productCategory->category_slug . "-" . $imageSeq . "." . $image->extension();
                $mimeType = $image->getClientMimeType();

                $image->storeAs($storagePath, $fileName);

                $filePath = $storagePath . "/" . $fileName;
                if (Storage::exists($filePath)) {
                    Media::create([
                        'model_category' => ProductCategory::MEDIA_CATEGORY,
                        'model_id' =>  $productCategory->id,
                        'viewport' => Media::DESKTOP,
                        'media_type' => $mimeType,
                        'media_name' => $fileName,
                        'path' => $filePath,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ]);
                }

                $imageSeq++;
            }

            DB::commit();

            return redirect(route("product-category.index"))
                ->with("response-message", "Create product category berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("product-category.index"))
                ->with("response-message", "Create product category gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCategory $productCategory)
    {
        $productCategory->load('images');
        foreach ($productCategory->images as $key => $image) {
            $productCategory->images[$key]->url = (new MediaStorage())->getFile($image->id, $image->media_name);
        }
        return view('product_category.product_category_edit', compact('productCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function update(ProductCategoryUpdateRequest $request, ProductCategory $productCategory)
    {
        DB::beginTransaction();
        try {
            $productCategory->category_name = $request->category_name;
            $productCategory->category_description = $request->category_description;
            $productCategory->created_by = $request->created_by;
            $productCategory->save();

            $productCategory->load('images');
            /**
             * Jika title article berubah
             */
            if ($productCategory->wasChanged('category_name')) {
                /**
                 * Update nama file sesuai category name yang baru
                 */
                $oldImageSeq = 1;
                foreach ($productCategory->images as $key => $image) {

                    $explodedName = explode(".", $image->media_name);
                    $extension = end($explodedName);

                    $newName = $productCategory->category_slug . "-" . $oldImageSeq . "." . $extension;
                    $newPath = Media::PRODUCT_CATEGORY_PATH . "/" . $productCategory->id . "/" . $newName;
                    if (Storage::exists($image->path)) {
                        Storage::move($image->path, $newPath);
                        $productCategory->images[$key]->media_name = $newName;
                        $productCategory->images[$key]->path = $newPath;
                        $productCategory->images[$key]->save();
                    }

                    $oldImageSeq++;
                }
            }

            $productCategory->unsetRelation('images');

            $newImageSeq = $productCategory->images()->withTrashed()->count();
            if ($request->hasFile('category_image')) {
                /**
                 * Flag delete file yang lama
                 */
                $productCategory->images()->update([
                    'deleted_at' => Carbon::now(),
                    'deleted_by' => Auth::user()->id
                ]);

                foreach ($request->category_image as $key => $image) {
                    $newImageSeq++;

                    $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::PRODUCT_CATEGORY_PATH . "/" . $productCategory->id;
                    $fileName = $productCategory->category_slug . "-" . $newImageSeq . "." . $image->extension();
                    $mimeType = $image->getClientMimeType();

                    $image->storeAs($storagePath, $fileName);

                    $filePath = $storagePath . "/" . $fileName;
                    if (Storage::exists($filePath)) {
                        Media::create([
                            'model_category' => ProductCategory::MEDIA_CATEGORY,
                            'model_id' =>  $productCategory->id,
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

            return redirect(route("product-category.index"))
                ->with("response-message", "Update product category berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("product-category.index"))
                ->with("response-message", "Update product category gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $productCategory)
    {
        DB::beginTransaction();
        try {
            $productCategory->deleted_at = Carbon::now();
            $productCategory->deleted_by = Auth::user()->id;
            $productCategory->save();


            $productCategory->images()->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->id
            ]);

            DB::commit();

            return redirect(route("product-category.index"))
                ->with("response-message", "Delete product category berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("product-category.index"))
                ->with("response-message", "Delete product category gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
