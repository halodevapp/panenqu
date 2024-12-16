<?php

namespace App\Http\Controllers;

use App\Helpers\MediaStorage;
use App\Models\Media;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventGaleryController extends Controller
{
    public function index()
    {
        $galery = Media::where('model_category', Media::EVENT_GALERY_MODEL)->get();

        foreach ($galery as $key => $image) {
            $galery[$key]->url = (new MediaStorage())->getFile($image->id, $image->media_name);
        }
        return view('event_galery.event_galery_index', compact('galery'));
    }

    public function create()
    {
        return view('event_galery.event_galery_create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            foreach ($request->galery_image as $key => $image) {
                $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::EVENT_GALERY_PATH;
                $mimeType = $image->getClientMimeType();

                $filePath = $image->store($storagePath);
                $fileExploded = explode("/", $filePath);
                $fileName = end($fileExploded);

                if (Storage::exists($filePath)) {
                    Media::create([
                        'model_category' => Media::EVENT_GALERY_MODEL,
                        'model_id' =>  0,
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

            return redirect(route("event-galery.index"))
                ->with("response-message", "Add galery berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("event-galery.index"))
                ->with("response-message", "Add galery gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function destroy(Media $eventGalery)
    {
        DB::beginTransaction();
        try {
            $eventGalery->deleted_at = Carbon::now();
            $eventGalery->deleted_by = Auth::user()->id;
            $eventGalery->save();

            DB::commit();

            return redirect(route("event-galery.index"))
                ->with("response-message", "Delete photo berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("event-galery.index"))
                ->with("response-message", "Delete photo gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
