<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Owner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\User;

class GroupController extends Controller
{
    public function show($id)
    {
        // TODO: use id to get group from database
        return view('pages.group');
    }

    public static function areFriends(User $user1, User $user2) // que é isto
    {
        return DB::table('friend_request')
            ->where('id_user_sender', $user1->id)
            ->where('id_user_sender', $user2->id)->where('accept_st', 'Accepted') ||
            DB::table('friend_request')
            ->where('id_user_sender', $user2->id)
            ->where('id_user_sender', $user1->id)->where('accept_st', 'Accepted');
    }


    public function create(Request $request)
    {

        // Insert group
        $group = new Group();

        $this->authorize('create', $group);

        $group->text = $request->input('name');
        $group->text = $request->input('description');
        $group->text = $request->input('visibility');

        // TODO : ADD PROFILE IMAGE
        $group->save();

        // Insert Group Owner
        $ownerId = $group->id_poster = Auth::user()->id;

        if ($this->addGroupOwner($ownerId, $group->id_group) == null) {
            return null; // Error adding owner
        }

        return $group;
    }


    public function delete($id)
    {

        $group = Group::find($id);

        $this->authorize('delete', $group);
        $group->delete();

        return $group;
    }



    public function addGroupOwner($idUser, $idGroup)
    {

        $owner = new Owner();

        $this->authorize('create', $owner);

        $owner->id_user = $idUser;
        $owner->id_group = $idGroup;

        $owner->save();

        return $owner;
    }


    public function removeGroupOwner($id)
    {
        $owner = Owner::find($id);
        $this->authorize('delete', $owner);
        $owner->delete();

        return $owner;
    }

}
