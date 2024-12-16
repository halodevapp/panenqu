<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestimoniStoreRequest;
use App\Http\Requests\TestimoniUpdateRequest;
use App\Models\Testimoni;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestimoniController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Testimoni::class, 'testimoni');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $testimonis = Testimoni::where(function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('profession', 'like', "%{$request->search}%")
                ->orWhere('rating', 'like', "%{$request->search}%")
                ->orWhere('testimoni', 'like', "%{$request->search}%");
        })->orderBy('id', 'desc')->paginate(10);
        return view('testimoni.testimoni_index', compact('testimonis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('testimoni.testimoni_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestimoniStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            Testimoni::create([
                'name' => $request->name,
                'profession' => $request->profession,
                'testimoni' => $request->testimoni,
                'rating' => $request->rating,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            DB::commit();

            return redirect(route("testimoni.index"))
                ->with("response-message", "Create testimoni berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("testimoni.index"))
                ->with("response-message", "Create testimoni gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Testimoni  $testimoni
     * @return \Illuminate\Http\Response
     */
    public function show(Testimoni $testimoni)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Testimoni  $testimoni
     * @return \Illuminate\Http\Response
     */
    public function edit(Testimoni $testimoni)
    {
        return view('testimoni.testimoni_edit', compact('testimoni'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Testimoni  $testimoni
     * @return \Illuminate\Http\Response
     */
    public function update(TestimoniUpdateRequest $request, Testimoni $testimoni)
    {
        DB::beginTransaction();
        try {
            $testimoni->name = $request->name;
            $testimoni->profession = $request->profession;
            $testimoni->testimoni = $request->testimoni;
            $testimoni->rating = $request->rating;
            $testimoni->updated_by = $request->updated_by;
            $testimoni->save();

            DB::commit();

            return redirect(route("testimoni.index"))
                ->with("response-message", "Update testimoni berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("testimoni.index"))
                ->with("response-message", "Update testimoni gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Testimoni  $testimoni
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimoni $testimoni)
    {
        DB::beginTransaction();
        try {
            $testimoni->deleted_at = Carbon::now();
            $testimoni->deleted_by = Auth::user()->id;
            $testimoni->save();

            DB::commit();

            return redirect(route("testimoni.index"))
                ->with("response-message", "Delete testimoni berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("testimoni.index"))
                ->with("response-message", "Delete testimoni gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
