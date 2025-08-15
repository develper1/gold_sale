<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $message1;

    public function __construct($name, $email, $message1)
    {
        $this->name = $name;
        $this->email = $email;
        $this->message1 = $message1;
    }

    public function build()
    {
        return $this->subject('New Contact Form Submission from ' . $this->name)
                    ->view('emails.contact-form');
    }
}
