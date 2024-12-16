<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerCategoryStoreRequest;
use App\Http\Requests\CustomerCategoryUpdateRequest;
use App\Models\CustomerCategory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerCategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(CustomerCategory::class, 'customer_category');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customerCategories = CustomerCategory::where(function ($query) use ($request) {
            $query->where('category_name', 'like', "%{$request->search}%");
        })->orderBy('sequence')->paginate(10);
        return view('customer_category.customer_category_index', compact('customerCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer_category.customer_category_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerCategoryStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            CustomerCategory::create([
                'category_name' => $request->category_name,
                'sequence' => $request->sequence,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            DB::commit();

            return redirect(route("customer-category.index"))
                ->with("response-message", "Create customer category berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("customer-category.index"))
                ->with("response-message", "Create customer category gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerCategory  $customerCategory .
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCategory $customerCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerCategory  $customerCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCategory $customerCategory)
    {
        return view('customer_category.customer_category_edit', compact('customerCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerCategory  $customerCategory
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerCategoryUpdateRequest $request, CustomerCategory $customerCategory)
    {
        DB::beginTransaction();
        try {

            $customerCategory->category_name = $request->category_name;
            $customerCategory->sequence = $request->sequence;
            $customerCategory->updated_by = Auth::user()->id;
            $customerCategory->save();

            DB::commit();

            return redirect(route("customer-category.index"))
                ->with("response-message", "Update customer category berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("customer-category.index"))
                ->with("response-message", "Update customer category gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerCategory  $customerCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerCategory $customerCategory)
    {
        DB::beginTransaction();
        try {
            $customerCategory->deleted_at = Carbon::now();
            $customerCategory->deleted_by = Auth::user()->id;
            $customerCategory->save();

            DB::commit();

            return redirect(route("customer-category.index"))
                ->with("response-message", "Delete customer category berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("customer-category.index"))
                ->with("response-message", "Delete customer category gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
