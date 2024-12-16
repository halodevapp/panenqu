<?php

namespace App\Jobs;

use App\Models\InstagramMedia;
use App\Models\SocmedAccessToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use PDO;

class InstagramRequestMedia
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
        try {
            //code...
            $token = SocmedAccessToken::where('client_id', env('IG_CLIENT_ID'))->first();

            $instagramRequest = Http::get('https://graph.instagram.com/me/media', [
                'fields' => 'media_type,media_url,permalink,thumbnail_url,timestamp,username,caption',
                'access_token' => $token->refresh_token
            ]);

            $instagram = json_decode($instagramRequest->body(), true);

            if (array_key_exists('data', $instagram)) {
                InstagramMedia::truncate();

                $instagramData = array();
                foreach ($instagram['data'] as $data) {

                    if ($data['media_type'] == 'IMAGE') {
                        $data['thumbnail_url'] = null;
                    }

                    array_push($instagramData, $data);
                }

                InstagramMedia::insert($instagramData);
            }
        } catch (\Throwable $th) {
            report($th);
        }
    }
}
