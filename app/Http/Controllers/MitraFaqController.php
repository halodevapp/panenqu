<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaqStoreRequest;
use App\Http\Requests\FaqUpdateRequest;
use App\Models\MitraFaq;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MitraFaqController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(MitraFaq::class, 'mitra_faq');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mitraFAQ = MitraFaq::where(function ($query) use ($request) {
            $query->where('question', 'like', "%{$request->search}%")
                ->orWhere('answer', 'like', "%{$request->search}%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('mitra_faq.mitra_faq_index', compact('mitraFAQ'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mitra_faq.mitra_faq_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            MitraFaq::create([
                'question' => $request->question,
                'answer' => $request->answer,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            DB::commit();

            return redirect(route("mitra-faq.index"))
                ->with("response-message", "Create FAQ berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("mitra-faq.index"))
                ->with("response-message", "Create FAQ gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MitraFaq  $mitraFaq
     * @return \Illuminate\Http\Response
     */
    public function show(MitraFaq $mitraFaq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MitraFaq  $mitraFaq
     * @return \Illuminate\Http\Response
     */
    public function edit(MitraFaq $mitraFaq)
    {
        return view('mitra_faq.mitra_faq_edit', compact('mitraFaq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MitraFaq  $mitraFaq
     * @return \Illuminate\Http\Response
     */
    public function update(FaqUpdateRequest $request, MitraFaq $mitraFaq)
    {
        DB::beginTransaction();
        try {

            $mitraFaq->question = $request->question;
            $mitraFaq->answer = $request->answer;
            $mitraFaq->updated_by = Auth::user()->id;
            $mitraFaq->save();

            DB::commit();

            return redirect(route("mitra-faq.index"))
                ->with("response-message", "Update FAQ berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("mitra-faq.index"))
                ->with("response-message", "Update FAQ gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MitraFaq  $mitraFaq
     * @return \Illuminate\Http\Response
     */
    public function destroy(MitraFaq $mitraFaq)
    {
        DB::beginTransaction();
        try {
            $mitraFaq->deleted_at = Carbon::now();
            $mitraFaq->deleted_by = Auth::user()->id;
            $mitraFaq->save();

            DB::commit();

            return redirect(route("mitra-faq.index"))
                ->with("response-message", "Delete FAQ berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("mitra-faq.index"))
                ->with("response-message", "Delete FAQ gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
