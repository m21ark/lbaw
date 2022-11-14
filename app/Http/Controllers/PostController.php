<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

  public static function areFriends(User $user1, User $user2) {
    return DB::table('friend_request')
      ->where('id_user_sender', $user1->id)
      ->where('id_user_sender', $user2->id)->where('accept_st', 'Accepted') ||
      DB::table('friend_request')
      ->where('id_user_sender', $user2->id)
      ->where('id_user_sender', $user1->id)->where('accept_st', 'Accepted');
  }

  public function show($id)
  {
    // TODO: use id to get post from database
    $post   = Post::withCount('likes')->find($id);
    // policy, nr_comments_post
    if (!$post->owner->visibility)
    {
      $this->authorize('view', $post);
    }
    return view('pages.post', ['post' => $post]);
  }

}
