<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function show()
    {

        if (!Auth::check())
            return redirect('403');

        $user = Auth::user();


        return view('pages.messages', ['user' => $user]);
    }
}
