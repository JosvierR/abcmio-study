<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserSentCreditMail extends Mailable
{
    use Queueable, SerializesModels;

    protected  $credit,$email,$fromEmail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($credit = null,$email = null, $fronEmail = null)
    {
        $this->credit = $credit;
        $this->fromEmail = $fronEmail;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@abcmio.com')
            ->subject('Asignación de Créditos')
            ->view('mails.users.user_sent_credits')->with(['total_credits'=>$this->credit,'email'=>$this->email,'fromEmail'=>$this->fromEmail]);
    }
}
