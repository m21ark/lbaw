<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use App\Http\Controllers\GroupController;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class GroupPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Group $group)
    {
        return GroupController::userInGroup($user, $group); // only if group is not public ...
    }

    public function create(User $user, Request $request)
    {
        return Group::where('name', '=', $request->input('name'))->firstOrFail() === null; // IT has only to be authenticated ... and can't have a group with the same name
    }

    public function update(User $user, Group $group)
    {
        return in_array($user->id, $group->owners->pluck('id_user')->toArray());
    }

    public function delete(User $user, Group $group)
    {
        return in_array($user->id, $group->owners->pluck('id_user')->toArray());
    }
}
