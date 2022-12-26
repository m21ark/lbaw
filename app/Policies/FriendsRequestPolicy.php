<?php

namespace App\Policies;

use App\Http\Controllers\PostController;
use App\Models\FriendsRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FriendsRequestPolicy
{
    use HandlesAuthorization;

    public function view(User $user, User $model)
    {
        return  $model->id == $user->id || $model->visibility || PostController::areFriends($user, $model);
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, FriendsRequest $friendsRequest)
    {
        return $friendsRequest->acceptance_status !== 'Rejected'; // THIS WILL succeed if object exist ... hence we can update it
    }

    public function delete(User $user, User $model)
    {
        return PostController::areFriends($user, $model);
    }
}
