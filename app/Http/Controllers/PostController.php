<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
  public function show($id)
  {
    // TODO: use id to get post from database
    return view('pages.post');
  }
}
