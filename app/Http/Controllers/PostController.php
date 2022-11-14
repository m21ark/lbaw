<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
  public function show($id)
  {
    // TODO: use id to get post from database
    $post   = Post::withCount('likes')->find($id);
    // policy
    return view('pages.post', ['post' => $post]);
  }

}
