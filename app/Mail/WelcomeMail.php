<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $password;
    protected $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$password,$token)
    {
        $this->user = $user;
        $this->password = $password;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = route('verify.email',$this->token);
        return $this
            ->from('info@abcmio.com')
            ->subject('Bienvenido a ABCMIO')
            ->view('mails.users.create')
            ->with(['email'=>$this->user->email,'password'=>$this->password,'url'=>$url]);
    }
}
