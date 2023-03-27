<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateAccount extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $fullname;
    public $username;
    public $token;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $fullname, $username, $token)
    {
        $this->email = $email;
        $this->fullname = $fullname;
        $this->username = $username;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->markdown('emails.forgetpass');
        return $this->subject('PowerShare - Open this link to verify your email and activate your password')->markdown('emails.createAcc');
    }
}
