<?php

namespace App\Policies;

use App\Http\Controllers\PostController;
use App\Models\FriendsRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin !== null) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->isAdmin() !== null;
    }

    public function view(User $user, User $model)
    {
        return  $model->id == $user->id || $model->visibility || PostController::areFriends($user, $model);
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, User $model)
    {
        return $user->id == $model->id;
    }

    public function delete(User $user, User $model)
    {
        // DELETE A FRIEND FROM FRIENDS LIST
        return PostController::areFriends($user, $model)
            || FriendsRequest::where('id_user_sender', '=', $user->id)
            ->where('id_user_receiver', '=', $model->id)
            || FriendsRequest::where('id_user_sender', '=', $user->id)
            ->where('id_user_receiver', '=', $model->id);
    }

    public function forceDelete(User $user, User $model)
    {
        return $user->id == $model->id;
    }
}
