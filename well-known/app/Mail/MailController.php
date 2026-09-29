<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailController extends Mailable
{
    use Queueable, SerializesModels;
  
    public $mail_details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail_details)
    {
        //$this->mail_details = $mail_details;
        //// commented for testing mail sending 15.06.2023
        // $this->mail_details = $mail_details->body;
        // $this->subject = $mail_details->subject;

        $this->mail_details = $mail_details['body'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.sentbody',['content' => $this->mail_details])->subject($this->subject);
        //return $this->view('emails.sentTest');
    }
}
