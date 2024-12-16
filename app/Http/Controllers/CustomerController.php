<?php

namespace App\Http\Controllers;

use App\Helpers\MediaStorage;
use App\Http\Requests\CustomerStoreRequest;
use App\Models\Customer;
use App\Models\CustomerCategory;
use App\Models\Media;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Customer::class, 'customer');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = Customer::where(function ($query) use ($request) {
            $query->where('customer_name', 'like', "%{$request->search}%")
                ->orWhereHas('category', function ($query) use ($request) {
                    $query->where('category_name', 'like', "%{$request->search}%");
                });
        })->orderBy('id', 'desc')->paginate(10);
        return view('customer.customer_index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = CustomerCategory::all();
        return view('customer.customer_create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::create([
                'customer_name' => $request->customer_name,
                'customer_category' => $request->customer_category,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);


            $imageSeq = 1;
            foreach ($request->customer_image as $key => $image) {
                $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::CUSTOMER_PATH . "/" . $customer->id;
                $fileName = Str::slug($customer->customer_name) . "-" . $imageSeq . "." . $image->extension();
                $mimeType = $image->getClientMimeType();

                $image->storeAs($storagePath, $fileName);

                $filePath = $storagePath . "/" . $fileName;
                if (Storage::exists($filePath)) {
                    Media::create([
                        'model_category' => Customer::MEDIA_CATEGORY,
                        'model_id' =>  $customer->id,
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

            return redirect(route("customer.index"))
                ->with("response-message", "Create customer berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("customer.index"))
                ->with("response-message", "Create customer gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $categories = CustomerCategory::all();

        $customer->load('images');
        foreach ($customer->images as $key => $image) {
            $customer->images[$key]->url = (new MediaStorage())->getFile($image->id, $image->media_name);
        }
        return view('customer.customer_edit', compact('categories', 'customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        DB::beginTransaction();
        try {
            $customer->customer_name = $request->customer_name;
            $customer->customer_category = $request->customer_category;
            $customer->updated_by = $request->updated_by;
            $customer->save();

            $customer->load('images');

            if ($customer->wasChanged('customer_name')) {

                $oldImageSeq = 1;
                foreach ($customer->images as $key => $image) {

                    $explodedName = explode(".", $image->media_name);
                    $extension = end($explodedName);

                    $newName = Str::slug($customer->customer_name) . "-" . $oldImageSeq . "." . $extension;
                    $newPath = Media::CUSTOMER_PATH . "/" . $customer->id . "/" . $newName;
                    if (Storage::exists($image->path)) {
                        Storage::move($image->path, $newPath);
                        $customer->images[$key]->media_name = $newName;
                        $customer->images[$key]->path = $newPath;
                        $customer->images[$key]->save();
                    }

                    $oldImageSeq++;
                }
            }

            $customer->unsetRelation('images');

            $newImageSeq = $customer->images()->withTrashed()->count();
            if ($request->hasFile('customer_image')) {

                $customer->images()->update([
                    'deleted_at' => Carbon::now(),
                    'deleted_by' => Auth::user()->id
                ]);

                foreach ($request->customer_image as $key => $image) {
                    $newImageSeq++;

                    $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::CUSTOMER_PATH . "/" . $customer->id;
                    $fileName = Str::slug($customer->customer_name) . "-" . $newImageSeq . "." . $image->extension();
                    $mimeType = $image->getClientMimeType();

                    $image->storeAs($storagePath, $fileName);

                    $filePath = $storagePath . "/" . $fileName;
                    if (Storage::exists($filePath)) {
                        Media::create([
                            'model_category' => Customer::MEDIA_CATEGORY,
                            'model_id' =>  $customer->id,
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

            return redirect(route("customer.index"))
                ->with("response-message", "Update customer berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("customer.index"))
                ->with("response-message", "Update customer gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        DB::beginTransaction();
        try {
            $customer->deleted_at = Carbon::now();
            $customer->deleted_by = Auth::user()->id;
            $customer->save();

            $customer->images()->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->id
            ]);

            DB::commit();

            return redirect(route("customer.index"))
                ->with("response-message", "Delete customer berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("customer.index"))
                ->with("response-message", "Delete customer gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
