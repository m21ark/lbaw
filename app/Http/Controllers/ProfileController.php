<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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

        // $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        // $out->writeln("Saved user");

        $user = User::where('username', $request->input('oldName'))->first();

        if ($user == null || $user->id != $request->input('idUser')) {
            return redirect()->route('home');
        }

        //$this->authorize('create', Auth::user());

        $user->username = $request->input('username'); // TODO: Check if username is unique
        $user->birthdate = $request->input('bdate');
        $user->visibility = $request->input('visibility') == 'on' ? true : false;
        $user->email = $request->input('email'); // TODO: Check if email is unique
        $user->bio = $request->input('bio');
        // TODO : ADD PROFILE IMAGE AND PASSWORD
        // TODO: EDIT ALSO USER INTERESTS


        $user->save();


        return $user;
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
