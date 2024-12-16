<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\MitraFormException;
use App\Http\Controllers\Controller;
use App\Http\Requests\MitraFormStoreRequest;
use App\Http\Resources\CommonResource;
use App\Mail\MitraCreatedMail;
use App\Models\EmailContact;
use App\Models\MitraForm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class MitraFormController extends Controller
{
    public function store(MitraFormStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $mitra = MitraForm::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message,
                'created_at' => Carbon::now()
            ]);

            $to = EmailContact::mitra()->get()->pluck('email');

            if ($to->isEmpty()) {
                $to = EmailContact::default()->get()->pluck('email');
            }

            Mail::to($to)->send(new MitraCreatedMail($mitra));

            DB::commit();

            return new CommonResource();
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th);
            throw new MitraFormException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        DB::rollBack();
    }
}
