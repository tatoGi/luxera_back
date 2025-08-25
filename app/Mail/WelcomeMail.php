<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject(__('Welcome to Momtabare'))
                    ->view('emails.welcome')
                    ->with([
                        'user' => $this->user,
                        'userName' => $this->user->name ?? null,
                        'language' => app()->getLocale(),
                    ]);
    }
}
