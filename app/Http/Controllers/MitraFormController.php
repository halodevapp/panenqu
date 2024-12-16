<?php

namespace App\Http\Controllers;

use App\Exports\MitraExport;
use App\Models\MitraForm;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MitraFormController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(MitraForm::class, 'mitra_form');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mitraForm = MitraForm::where(function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('phone', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%");
        })->orderBy('id', 'desc')->paginate(10);
        return view('mitra_form.mitra_form_index', compact('mitraForm'));
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
     * @param  \App\Models\MitraForm  $mitraForm
     * @return \Illuminate\Http\Response
     */
    public function show(MitraForm $mitraForm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MitraForm  $mitraForm
     * @return \Illuminate\Http\Response
     */
    public function edit(MitraForm $mitraForm)
    {
        return view('mitra_form.mitra_form_edit', compact('mitraForm'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MitraForm  $mitraForm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MitraForm $mitraForm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MitraForm  $mitraForm
     * @return \Illuminate\Http\Response
     */
    public function destroy(MitraForm $mitraForm)
    {
        //
    }

    public function export(Request $request)
    {
        $this->authorize('export', MitraForm::class);
        return Excel::download(new MitraExport, 'mitra_panenqu.xlsx');
    }
}
