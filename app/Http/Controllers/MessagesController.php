<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function show()
    {
        return view('pages.messages');
    }
}
