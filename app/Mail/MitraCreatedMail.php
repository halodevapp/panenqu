<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MitraCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mitra;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mitra)
    {
        $this->mitra = $mitra;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("Mitra Form")
            ->view('email.mitra_created');
    }
}
