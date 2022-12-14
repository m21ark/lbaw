<?php

namespace App\Http\Controllers;

use App\Mail\Mailtrap;
use Illuminate\Support\Facades\Mail as Mail;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class PasswordRecoverController extends Controller
{
    // sendEmail method.
    public function sendRecoverEmail($username, $email, $token)
    {
        $mailData = [
            'name' => $username,
            'email' => $email,
            'token' => $token
        ];

        Mail::to($mailData['email'])->send(new Mailtrap($mailData));

        // dd("Email was sent successfully.");
    }

    public function validatePasswordRequest(Request $request)
    {
        //You can add validation login here
        $user = DB::table('user')->where('email', '=', $request->email)
            ->first();

        if ($user === null) {
            // TODO: Ricardo faz a mesma magia q fizeste no login
            return redirect()->back()->withErrors(['email' => trans('Email does not exist')]);
        }

        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => Str::random(40),
        ]);

        //Get the token just created above
        $tokenData = DB::table('password_resets')->where('email', $request->email)->first();

        $this->sendRecoverEmail($user->username, $user->email, $tokenData->token);
        return redirect()->route('forgot-password-sent');
    }


    public function resetPassword()
    {
        return null;
    }
}
