<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ContactFormException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormStoreRequest;
use App\Http\Resources\CommonResource;
use App\Mail\ContactCreatedMail;
use App\Models\ContactForm;
use App\Models\EmailContact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    public function store(ContactFormStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $contact = ContactForm::create([
                'contact_category' => $request->contact_category,
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'created_at' => Carbon::now()
            ]);


            $contact->load(['category']);

            $to = EmailContact::contact()->get()->pluck('email');

            if ($to->isEmpty()) {
                $to = EmailContact::default()->get()->pluck('email');
            }

            Mail::to($to)->send(new ContactCreatedMail($contact));

            DB::commit();

            return new CommonResource();
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th);
            throw new ContactFormException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        DB::rollBack();
    }
}
