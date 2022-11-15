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
    $post   = Post::withCount('likes', 'comments')->find($id);
    // policy, nr_comments_post
    if (!$post->owner->visibility)
    {
      $this->authorize('view', $post);
    }
    return view('pages.post', ['post' => $post]);
  }

  public function feed(Request $request) {

    if ($request->route('type_feed') === "viral") {
      $posts = Post::
        with('owner')
        //where('likes', '>', 1)
        ->withCount('likes', 'comments')
        //->having('likes_count', '>', 1)
        ->orderBy('likes_count', 'desc') // TODO: take the date into consideration
        ->limit(5)
        ->get();
        
    }
    /*
    $posts_view = '';
    foreach($posts as $post) {
      $posts_view += view('partials.post', ['post' => $post]);
    }
    */
    //return json_encode([1, 2, 3]);
    return json_encode($posts);
  }

  public function create(Request $request) 
  {

    // TODO ::: TESTAR
    
    $post = new Post();
    /*
    $this->authorize('create', $post);

    $post->text = $request->input('text');
    $post->id_poster = Auth::user()->id; 
    
    if (isset($request->input('group'))) {
      $post->id_group = $request->input('group');
    }
    // TODO : ADD IMAGES

    $post->save();

    return $post;
    */
  }

  public function delete($id) 
  {
    // TODO ::: TESTAR
    $post = Post::find($id);

    $this->delete('delete', $post);

    $post->delete();
    return $post;
  }



}
