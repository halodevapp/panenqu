<?php

namespace App\Mail;

use App\Helpers\MediaStorage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ArticlePublished extends Mailable
{
    use Queueable, SerializesModels;

    public $article;
    public $banner;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($article, $email)
    {
        $this->article = $article;
        $this->email = $email;
        $this->banner = env('AWS_MEDIA_PUBLIC') . $article->banner[0]->path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("Panenqu New Article")
            ->view('email.article_published');
    }
}
