<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class Mailtrap extends Mailable
{

    public $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    public function envelope()
    {
        return new Envelope(
            from: new Address('social@nexus.net', 'Nexus Social'),
            subject: 'Welcome to Nexus Social',
        );
    }

    public function content()
    {
        return new Content(
            view: 'pages.emails.password_recovery',
        );
    }
}
