<?php

namespace App\Http\Controllers;

use App\Mail\Mailtrap;
use App\Models\User;
use Illuminate\Support\Facades\Mail as Mail;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PasswordRecoverController extends Controller
{
    // sendEmail method.
    public function sendRecoverEmail($username, $email, $token)
    {

        $token_link = config('app.url') . '/password/reset/' . $token;

        // THIS IS A GUEST endpoint ... no need for POLICY

        $mailData = [
            'name' => $username,
            'email' => $email,
            'token_link' => $token_link
        ];

        Mail::to($mailData['email'])->send(new Mailtrap($mailData));
    }

    public function validatePasswordRequest(Request $request)
    {
        //You can add validation login here
        $user = DB::table('user')->where('email', '=', $request->email)
            ->first();

        // THIS IS A GUEST endpoint ... no need for POLICY

        if ($user === null) {
            return redirect()->back()->with('error', 'Not a valid email'); // input validation
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

    public function showChangePassword()
    {
        if (!auth()->check())
            return redirect()->route('home');

        $token = Str::random(40);

        DB::table('password_resets')->insert([
            'email' => Auth::user()->email,
            'token' => $token,
        ]);

        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {

        // THIS IS A GUEST endpoint ... no need for POLICY

        if($request->password != $request->password_confirmation)
            return redirect()->back()->with('error', 'Passwords do not match'); // input validation

        if(strlen($request->password) < 8)
            return redirect()->back()->with('error', 'Password needs to be at least 8 characters long'); // input validation

        $password = $request->password;

        // Validate the token
        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();

        // Redirect the user back to the password reset request form if the token is invalid
        if ($tokenData === null) return view('auth.reset-password', ['token' => 'invalid_token']);

        $user = User::where('email', $tokenData->email)->first();
        if ($user === null) return redirect()->route('home');

        //Hash and update the new password
        $user->password = Hash::make($password);

        $user->update();

        //login the user immediately they change password successfully
        Auth::login($user);

        //Delete the token
        DB::table('password_resets')->where('email', $user->email)->delete();

        return redirect()->route('home');
    }
}
