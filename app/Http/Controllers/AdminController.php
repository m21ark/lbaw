<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Group;
use App\Models\Like;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function show()
    {
        $this->authorize('view');

        $statistics =[
            'posts_c' => Post::count(),
            'users_c' => User::count(),
            'groups_c' => Group::count(),
            'comments_c' => Comment::count(),
            'likes_c' => Like::count() + CommentLike::count()
        ];
        
        return view('pages.admin', ['statistics' => $statistics]);
    }
}
