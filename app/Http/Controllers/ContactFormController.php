<?php

namespace App\Http\Controllers;

use App\Models\ContactForm;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactFormController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ContactForm::class, 'contact_form');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contactForm = ContactForm::where(function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('subject', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
                ->orWhereHas('category', function ($query) use ($request) {
                    $query->where('category_name', 'like', "%{$request->search}%");
                });
        })->orderBy('id', 'DESC')->paginate(10);
        return view('contact_form.contact_form_index', compact('contactForm'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactForm  $contactForm
     * @return \Illuminate\Http\Response
     */
    public function show(ContactForm $contactForm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactForm  $contactForm
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactForm $contactForm)
    {
        return view('contact_form.contact_form_edit', compact('contactForm'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactForm  $contactForm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactForm $contactForm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactForm  $contactForm
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactForm $contactForm)
    {
        DB::beginTransaction();
        try {
            $contactForm->deleted_at = Carbon::now();
            $contactForm->deleted_by = Auth::user()->id;
            $contactForm->save();

            DB::commit();

            return redirect(route("contact-form.index"))
                ->with("response-message", "Delete contact form berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("contact-form.index"))
                ->with("response-message", "Delete contact form gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
