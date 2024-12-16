<?php

namespace App\Jobs;

use App\Models\InstagramMedia;
use App\Models\SocmedAccessToken;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class InstagramRequestToken
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $token = SocmedAccessToken::where('client_id', env('IG_CLIENT_ID'))->first();
        $instagram = Http::get('https://graph.instagram.com/refresh_access_token', [
            'grant_type' => 'ig_refresh_token',
            'access_token' => $token->refresh_token
        ]);

        $response = json_decode($instagram->body());
        if (property_exists($response, 'access_token')) {
            SocmedAccessToken::create([
                'client_id' => $token->client_id,
                'user_id' => $token->user_id,
                'access_token' => $token->refresh_token,
                'refresh_token' => $response->access_token,
            ]);

            $token->deleted_at = Carbon::now();
            $token->save();
        }
    }
}
