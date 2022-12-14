<?php

namespace App\Http\Controllers;

// Added to support email sending.

use App\Mail\Mailtrap;
use Illuminate\Support\Facades\Mail as Mail;

class TestController extends Controller
{
    // sendEmail method.
    public function sendEmail()
    {

        $mailData = [
            'name' => 'Marco André',
            'email' => 'up202004891@g.uporto.pt',
        ];

        Mail::to($mailData['email'])->send(new Mailtrap($mailData));

        dd("Email was sent successfully.");
    }
}
