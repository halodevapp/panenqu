<?php

namespace App\Listeners;

use App\Events\ArticleCreated;
use App\Jobs\SendArticleSubscriberNotification as JobsSendArticleSubscriberNotification;
use App\Mail\ArticlePublished;
use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class SendArticleSubscriberNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ArticleCreated  $event
     * @return void
     */
    public function handle(ArticleCreated $event)
    {
        $subscribers = Subscriber::whereNull('unsubscribe_date')->get();
        foreach ($subscribers as $subscriber) {
            $email = Crypt::encryptString($subscriber->email);
            Mail::to($subscriber->email)->queue(new ArticlePublished($event->article, $email));
        }
    }

    public function failed(ArticleCreated $event, $exception)
    {
    }
}
