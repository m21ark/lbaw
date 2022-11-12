<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        // TODO: get user from database
        return view('pages.profile');
    }
}
