<?php

namespace App\Policies;

use App\Http\Controllers\PostController;
use App\Http\Controllers\GroupController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;


class CommentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Comment $comment)
    {
        $contr = new PostController();
        return $contr->authorize('view', $comment->post);
    }

    public function create(User $user, Post $post)
    {
        $contr = new PostController();
        return $contr->authorize('view', $post);
    }

    public function update(User $user, Comment $comment)
    {
        return $user->id == $comment->id_commenter;
    }


    public function delete(User $user, Comment $comment)
    {
        return $user->id == $comment->id_commenter;
    }

}
