<?php

namespace App\Policies;

use App\Models\FriendsRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FriendsRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendsRequest  $friendsRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        return  $model->id == $user->id || $model->visibility || PostController::areFriends($user, $model);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendsRequest  $friendsRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, FriendsRequest $friendsRequest)
    {
        return $friendsRequest->acceptance_status !== 'Rejected'; // THIS WILL succeed if object exist ... hence we can update it 
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendsRequest  $friendsRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        return PostController::areFriends($user, $model);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendsRequest  $friendsRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, FriendsRequest $friendsRequest)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendsRequest  $friendsRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, FriendsRequest $friendsRequest)
    {
        //
    }
}
