<?php

namespace App\Policies;

use App\Models\GroupJoinRequest;
use App\Models\User;
use App\Models\Group;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupJoinRequestPolicy
{
    use HandlesAuthorization;


    public function create(User $user, Group $group)
    {
        return true;
    }

    public function update(User $user, GroupJoinRequest $groupJoinRequest)
    {
        return $groupJoinRequest->acceptance_status !== 'Rejected';
    }

    public function delete(User $user, GroupJoinRequest $groupJoinRequest)
    {
        return $user->id == $groupJoinRequest->id_user;
    }

}
