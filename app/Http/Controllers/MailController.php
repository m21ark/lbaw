<?php

namespace App\Http\Controllers;

use App\Mail\Mailtrap;
use Illuminate\Support\Facades\Mail as Mail;


use App\Models\User;


class MailController extends Controller
{
    // sendEmail method.
    public function sendEmail($user_id)
    {
        $user = User::find($user_id);

        $mailData = [
            'name' => $user->username,
            'email' => $user->email,
        ];

        Mail::to($mailData['email'])->send(new Mailtrap($mailData));

        dd("Email was sent successfully.");
    }
}
