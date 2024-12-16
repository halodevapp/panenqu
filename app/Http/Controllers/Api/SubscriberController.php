<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\SubscriberException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriberStoreRequest;
use App\Http\Resources\CommonResource;
use App\Mail\SubscribedMail;
use App\Mail\UnsubscribedMail;
use App\Models\EmailContact;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Crypt;

class SubscriberController extends Controller
{
    public function store(SubscriberStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $subscribe = Subscriber::create([
                'email' => $request->email,
                'subscribe_date' => Carbon::now()
            ]);

            $to = EmailContact::subscribe()->get()->pluck('email');

            if ($to->isEmpty()) {
                $to = EmailContact::default()->get()->pluck('email');
            }

            Mail::to($to)->send(new SubscribedMail($subscribe));

            DB::commit();

            return new CommonResource([], "Berhasil subscribe panenqu");
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th);
            throw new SubscriberException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        DB::rollBack();
    }

    public function unsubscribe($email)
    {
        DB::beginTransaction();
        try {
            $emailDecrypted = Crypt::decryptString($email);

            $subscriber = Subscriber::where('email', $emailDecrypted)->first();

            if ($subscriber && $subscriber->unsubscribe_date == null) {
                $subscriber->unsubscribe_date = Carbon::now();
                $subscriber->save();

                DB::commit();

                Mail::to($emailDecrypted)->send(new UnsubscribedMail($emailDecrypted));

                $toAdmin = EmailContact::subscribe()->get()->pluck('email');

                if ($toAdmin->isEmpty()) {
                    $toAdmin = EmailContact::default()->get()->pluck('email');
                }

                Mail::to($toAdmin)->send(new UnsubscribedMail($emailDecrypted));
            }


            return redirect(env('FE_URL'));
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th);
            throw new SubscriberException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        DB::rollBack();
    }
}
