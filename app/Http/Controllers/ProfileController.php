<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->first();

        if ($user == null) {
            //No user with that name so we return to the home page
            return redirect()->route('home');
        }

        return view('pages.profile', ['user' => $user]);
    }

    public function edit(Request $request)
    {

        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("Saved user");

        //$user = User::where('username', $request->input('oldName'))->first();

        $user = Auth::user();


        //if ($user == null || $user->id != $request->input('idUser')) {
        //    return redirect()->route('home');
        //}
//

        //$this->authorize('create');
        $out->writeln($user->username);
        $user->username = $request->input('username') ?? $user->username; // TODO: Check if username is unique
        $user->birthdate = $request->input('bdate') ?? $user->birthdate;
        $user->visibility = $request->input('visibility') == 'on' ? true : false;
        $user->email = $request->input('email') ?? $user->email; // TODO: Check if email is unique
        $user->bio = $request->input('bio') ?? $user->bio;
        // TODO : ADD PROFILE IMAGE AND PASSWORD
        // TODO: EDIT ALSO USER INTERESTS

        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        if ($request->photo !== null) {

            $user->photo = 'user/' . strval($user->id) . '.jpg';

            try {
                $request->file('photo')->move(public_path('user/'), $user->id . '.jpg');
            }
            catch(Exception $e) {
                $out->writeln($e->getMessage());
                DB::rollBack();
            }

        }
        DB::commit();
        $user->save();


        return redirect()->route('profile', $user->username);
    }


    public function delete($username)
    {

        // Mesmo problema que em grupos... triggers impedem de apagar
        // aqui julgo que é a cena de n poder deixar grupos sem outros owners
        $user = User::where('username', $username)->first();

        $user->delete();

        return redirect()->route('home');
    }
}
