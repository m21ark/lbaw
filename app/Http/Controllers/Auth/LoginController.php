<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function getUser()
    {
        return $request->user();
    }

    public function home()
    {
        return redirect('login');
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("START_1");

        try {
            $user = Socialite::driver('google')->user();
            $out->writeln("START_2");
        } catch (\Exception $e) {
            return redirect('/login');
            $out->writeln("START_3");
        }

        $out->writeln("START_4");

        $out->writeln("START_6");

        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();

        $out->writeln("START_7");


        if ($existingUser !== null) {
            // log them in
            $out->writeln("START_8");
            auth()->login($existingUser);
            $out->writeln("START_9");
        } else {
            $out->writeln("START_10");
            // create a new user
            $newUser                  = new User;
            $out->writeln("START_11");
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $out->writeln("START_12");
            //$newUser->avatar          = $user->avatar;
            //$newUser->avatar_original = $user->avatar_original;
            $newUser->save();
            $out->writeln("START_13");
            auth()->login($newUser, true);
            $out->writeln("START_14");
        }
        $out->writeln("START_15");
        return redirect()->to('/home');
    }
}
