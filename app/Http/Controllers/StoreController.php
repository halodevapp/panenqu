<?php

namespace App\Http\Controllers;

use App\Helpers\MediaStorage;
use App\Http\Requests\StoreCreateRequest;
use App\Http\Requests\StoreUpdateRequest;
use App\Models\Media;
use App\Models\Store;
use App\Models\StoreType;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Store::class, 'store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stores = Store::with('StoreType')->where(function ($query) use ($request) {
            $query->where('store_name', 'like', "%{$request->search}%")
                ->orWhereHas('StoreType', function ($query) use ($request) {
                    $query->where('type_name', 'like', "%{$request->search}%");
                });
        })->orderBy('id', 'DESC')->paginate(10);
        return view('store.store_index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $storeTypes = StoreType::all();
        return view('store.store_create', compact('storeTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $store = Store::create([
                'store_name' => $request->store_name,
                'store_type_id' => $request->store_type_id,
                'store_phone' => $request->store_type_id == Store::STORE_OFFLINE ? $request->store_phone : null,
                'store_location' => $request->store_type_id == Store::STORE_OFFLINE ? $request->store_location : null,
                'store_latitude' => $request->store_type_id == Store::STORE_OFFLINE ? $request->latitude : null,
                'store_longitude' => $request->store_type_id == Store::STORE_OFFLINE ? $request->longitude : null,
                'store_link' => $request->store_type_id == Store::STORE_ONLINE ? $request->store_link : null,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);


            if ($request->store_type_id == Store::STORE_ONLINE) {
                $imageSeq = 1;
                foreach ($request->store_image as $key => $image) {
                    $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::STORE_PATH . "/" . $store->id;
                    $fileName = Str::slug($store->store_name) . "-" . $imageSeq . "." . $image->extension();
                    $mimeType = $image->getClientMimeType();

                    $image->storeAs($storagePath, $fileName);

                    $filePath = $storagePath . "/" . $fileName;
                    if (Storage::exists($filePath)) {
                        Media::create([
                            'model_category' => Store::MEDIA_STORE_ONLINE,
                            'model_id' =>  $store->id,
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
            }


            DB::commit();

            return redirect(route("store.index"))
                ->with("response-message", "Create store berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("store.index"))
                ->with("response-message", "Create store gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        $storeTypes = StoreType::all();

        $store->load('storeImageOnline');
        foreach ($store->storeImageOnline as $key => $image) {
            $store->storeImageOnline[$key]->url = (new MediaStorage())->getFile($image->id, $image->media_name);
        }
        return view('store.store_edit', compact('storeTypes', 'store'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateRequest $request, Store $store)
    {
        DB::beginTransaction();
        try {
            $store->store_name = $request->store_name;
            $store->store_type_id = $request->store_type_id;
            $store->store_phone = $request->store_type_id == Store::STORE_OFFLINE ? $request->store_phone : null;
            $store->store_location = $request->store_type_id == Store::STORE_OFFLINE ? $request->store_location : null;
            $store->store_latitude = $request->store_type_id == Store::STORE_OFFLINE ? $request->latitude : null;
            $store->store_longitude = $request->store_type_id == Store::STORE_OFFLINE ? $request->longitude : null;
            $store->store_link = $request->store_type_id == Store::STORE_ONLINE ? $request->store_link : null;
            $store->updated_by = $request->updated_by;
            $store->save();


            $store->load('storeImageOnline');

            if ($store->wasChanged('store_name') && $request->store_type_id == Store::STORE_ONLINE) {

                $oldImageSeq = 1;
                foreach ($store->storeImageOnline as $key => $image) {

                    $explodedName = explode(".", $image->media_name);
                    $extension = end($explodedName);

                    $newName = Str::slug($store->store_name) . "-" . $oldImageSeq . "." . $extension;
                    $newPath = Media::STORE_PATH . "/" . $store->id . "/" . $newName;
                    if (Storage::exists($image->path)) {
                        Storage::move($image->path, $newPath);
                        $store->storeImageOnline[$key]->media_name = $newName;
                        $store->storeImageOnline[$key]->path = $newPath;
                        $store->storeImageOnline[$key]->save();
                    }

                    $oldImageSeq++;
                }
            }

            $store->unsetRelation('storeImageOnline');

            $newImageSeq = $store->storeImageOnline()->withTrashed()->count();
            if ($request->hasFile('store_image')) {

                $store->storeImageOnline()->update([
                    'deleted_at' => Carbon::now(),
                    'deleted_by' => Auth::user()->id
                ]);

                foreach ($request->store_image as $key => $image) {
                    $newImageSeq++;

                    $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::STORE_PATH . "/" . $store->id;
                    $fileName = Str::slug($store->store_name) . "-" . $newImageSeq . "." . $image->extension();
                    $mimeType = $image->getClientMimeType();

                    $image->storeAs($storagePath, $fileName);

                    $filePath = $storagePath . "/" . $fileName;
                    if (Storage::exists($filePath)) {
                        Media::create([
                            'model_category' => Store::MEDIA_STORE_ONLINE,
                            'model_id' =>  $store->id,
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

            return redirect(route("store.index"))
                ->with("response-message", "Update store berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("store.index"))
                ->with("response-message", "Update store gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        DB::beginTransaction();
        try {
            $store->deleted_at = Carbon::now();
            $store->deleted_by = Auth::user()->id;
            $store->save();

            $store->storeImageOnline()->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->id
            ]);

            DB::commit();

            return redirect(route("store.index"))
                ->with("response-message", "Delete store berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("store.index"))
                ->with("response-message", "Delete store gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
