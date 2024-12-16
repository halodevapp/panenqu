<?php

namespace App\Http\Middleware;

use App\Exceptions\RecaptchaException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class Recaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        try {
            $verify = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify", [
                "secret" => env('CAPTCHA_SECRET'),
                "response" => $request->captcha
            ]);

            $response = json_decode($verify->body());
            if (!$response->success) {

                $error = collect($response)->toArray();

                $errorCode = $error['error-codes'][0] ?: '';

                throw new RecaptchaException("Recaptcha is not valid {$errorCode}", Response::HTTP_FORBIDDEN);
            }

            return $next($request);
        } catch (\Throwable $th) {
            report($th);
            throw $th;
        }
    }
}
