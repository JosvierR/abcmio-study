<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($password = null)
    {
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = route("home");
        return $this->markdown('emails.password_reset')
            ->from('info@abcmio.com')
            ->subject('Bienvenido a ABCMIO')
            ->with(["password"=>$this->password,"url"=>$url]);
    }
}
