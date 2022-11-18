<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show($query)
    {
        // TODO: get query from database
        return view('pages.search');
    }



    
}
