<?php

namespace App\Http\Controllers;

use App\Helpers\MediaStorage;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductHasStore;
use App\Models\Store;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::where(function ($query) use ($request) {
            $query->where('product_name', 'like', "%{$request->search}%")
                ->orWhereHas('category', function ($query) use ($request) {
                    $query->where('category_name', 'like', "%{$request->search}%");
                });
        })
            ->orderByRaw("ISNULL(sequence) asc")
            ->orderBy("sequence", "asc")
            ->orderBy('id', 'DESC')->paginate(10);

        return view('product.product_index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategory::all();
        $stores = Store::where('store_type_id', Store::STORE_ONLINE)->get();
        return view('product.product_create', compact('categories', 'stores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = Product::create([
                'product_name' => $request->product_name,
                'product_category' => $request->product_category,
                'product_description' => $request->product_description,
                'sequence' => $request->sequence,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            if ($request->exists('marketplace_link')) {
                $productHasStore = array();
                foreach ($request->marketplace_link as $key => $marketplace) {
                    array_push($productHasStore, [
                        'product_id' => $product->id,
                        'store_id' => $key,
                        'product_store_link' => $marketplace,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ]);
                }

                ProductHasStore::upsert($productHasStore, ['product_id', 'store_id'], ['product_store_link']);
            }

            foreach ($request->product_image as $key => $image) {
                $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::PRODUCT_PATH . "/" . $product->id;
                $fileName = $product->product_slug . "-" . $key . "." . $image->extension();
                $mimeType = $image->getClientMimeType();

                $image->storeAs($storagePath, $fileName);

                $filePath = $storagePath . "/" . $fileName;
                if (Storage::exists($filePath)) {
                    Media::create([
                        'model_category' => Product::MEDIA_CATEGORY,
                        'model_id' =>  $product->id,
                        'viewport' => Media::DESKTOP,
                        'media_type' => $mimeType,
                        'media_name' => $fileName,
                        'path' => $filePath,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ]);
                }
            }


            DB::commit();

            return redirect(route("product.index"))
                ->with("response-message", "Create product berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("product.index"))
                ->with("response-message", "Create product gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        $stores = Store::with(['product' => function ($query) use ($product) {
            $query->where('product_id', $product->id);
        }])->where('store_type_id', Store::STORE_ONLINE)->get();

        $product->load('images');
        foreach ($product->images as $key => $image) {
            $product->images[$key]->url = (new MediaStorage())->getFile($image->id, $image->media_name);
        }

        return view('product.product_edit', compact('product', 'categories', 'stores'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {

            $product->product_name = $request->product_name;
            $product->product_category = $request->product_category;
            $product->product_description = $request->product_description;
            $product->sequence = $request->sequence;
            $product->updated_by = Auth::user()->id;
            $product->save();

            if ($request->exists('marketplace_link')) {

                $productHasStore = array();
                foreach ($request->marketplace_link as $key => $marketplace) {
                    array_push($productHasStore, [
                        'product_id' => $product->id,
                        'store_id' => $key,
                        'product_store_link' => $marketplace,
                        'updated_by' => Auth::user()->id,
                    ]);
                }

                ProductHasStore::upsert($productHasStore, ['product_id', 'store_id'], ['product_store_link']);
            }

            $product->load('images');

            if ($product->wasChanged('product_name')) {

                $oldSeq = 1;
                foreach ($product->images as $key => $image) {

                    $explodedName = explode(".", $image->media_name);
                    $extension = end($explodedName);

                    $newName = $product->product_slug . "-" . $oldSeq . "." . $extension;
                    $newPath = Media::PRODUCT_PATH . "/" . $product->id . "/" . $newName;
                    Storage::move($image->path, $newPath);

                    $product->images[$key]->media_name = $newName;
                    $product->images[$key]->path = $newPath;
                    $product->images[$key]->save();

                    $oldSeq++;
                }
            }

            $product->unsetRelation('images');
            $newSeq = $product->images()->withTrashed()->count();
            if ($request->hasFile('product_image')) {
                foreach ($request->product_image as $key => $image) {
                    $newSeq++;

                    $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::PRODUCT_PATH . "/" . $product->id;
                    $fileName = $product->product_slug . "-" . $newSeq . "." . $image->extension();
                    $mimeType = $image->getClientMimeType();

                    $image->storeAs($storagePath, $fileName);

                    $filePath = $storagePath . "/" . $fileName;
                    if (Storage::exists($filePath)) {
                        Media::create([
                            'model_category' => Product::MEDIA_CATEGORY,
                            'model_id' =>  $product->id,
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

            return redirect(route("product.index"))
                ->with("response-message", "Update product berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("product.index"))
                ->with("response-message", "Update product gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {

        DB::beginTransaction();
        try {

            $product->deleted_at = Carbon::now();
            $product->deleted_by = Auth::user()->id;
            $product->save();

            $product->images()->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->id
            ]);

            $product->stores()->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->id
            ]);

            DB::commit();

            return redirect(route("product.index"))
                ->with("response-message", "Delete product berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("product.index"))
                ->with("response-message", "Delete product gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
