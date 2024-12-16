<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormCategoryStoreRequest;
use App\Http\Requests\ContactFormCategoryUpdateRequest;
use App\Models\ContactFormCategory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactFormCategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ContactFormCategory::class, 'contact_category');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $formCategories = ContactFormCategory::where(function ($query) use ($request) {
            $query->where('category_name', 'like', "%{$request->search}%");
        })->orderBy('id', 'desc')->paginate(10);
        return view('contact_form_category.contact_form_category_index', compact('formCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contact_form_category.contact_form_category_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactFormCategoryStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            ContactFormCategory::create([
                'category_name' => $request->category_name,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            DB::commit();

            return redirect(route("contact-category.index"))
                ->with("response-message", "Create contact form category berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("contact-category.index"))
                ->with("response-message", "Create contact form category gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactFormCategory  $contactCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ContactFormCategory $contactCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactFormCategory  $contactCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactFormCategory $contactCategory)
    {
        return view('contact_form_category.contact_form_category_edit', compact('contactCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactFormCategory  $contactCategory
     * @return \Illuminate\Http\Response
     */
    public function update(ContactFormCategoryUpdateRequest $request, ContactFormCategory $contactCategory)
    {
        DB::beginTransaction();
        try {

            $contactCategory->category_name = $request->category_name;
            $contactCategory->updated_by = Auth::user()->id;
            $contactCategory->save();

            DB::commit();

            return redirect(route("contact-category.index"))
                ->with("response-message", "Update contact form category berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("contact-category.index"))
                ->with("response-message", "Update contact form category gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactFormCategory  $contactCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactFormCategory $contactCategory)
    {
        DB::beginTransaction();
        try {
            $contactCategory->deleted_at = Carbon::now();
            $contactCategory->deleted_by = Auth::user()->id;
            $contactCategory->save();

            DB::commit();

            return redirect(route("contact-category.index"))
                ->with("response-message", "Delete contact form category berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("contact-category.index"))
                ->with("response-message", "Delete contact form category gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
