<?php

namespace App\Mail;

use App\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SentMessageFromProductDetailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $property ;
    public $post;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Property $property,$post)
    {
        $this->property = $property;
        $this->post=$post;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.products.message')
            ->from('support@abcmio.com')
            ->subject('ABCMIO Message')
            ->with(["product"=>$this->property,"post"=>$this->post]);;
    }
}
