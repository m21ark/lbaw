<?php

namespace App\Policies;

use App\Http\Controllers\GroupController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;

class PostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return True;
    }

    public function view(User $user, Post $post)
    {

        if (isset($post->group)) {
            return GroupController::userInGroup($user, $post->group);
        }

        return PostController::areFriends($user, $post->owner) || $post->id_poster == Auth::id();
    }

    public function create(User $user, Post $post)
    {
        return Auth::check();
    }

    public function update(User $user, Post $post)
    {
        return in_array(Auth::user()->id, $user->posts->pluck('id_poster')->toArray());
    }

    public function delete(User $user, Post $post)
    {
        if ($post->owner->id == $user->id)
            return true;

        // Test if is group owner trying to remove post
        if (isset($post->group)) {
            foreach ($post->group->owners as $owner) {
                if ($owner->id_user == Auth::user()->id)
                    return true;
            }
        }
    }
}
