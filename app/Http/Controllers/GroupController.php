<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function show($id)
    {
        // TODO: use id to get group from database
        return view('pages.group');
    }
}
