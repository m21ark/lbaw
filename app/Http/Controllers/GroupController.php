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
    public function show($name)
    {
        // TODO: use id to get group from database

        $group = Group::where('name', $name)->first();

        if (!$group->visibility) {
            $this->authorize('view', $group);
        }
        return view('pages.group', ['group' => $group]);
    }

    public static function userInGroup(User $user1, Group $group) 
    {
        return DB::table('group_join_request')
            ->where('id_user', $user1->id)
            ->where('id_group', $group->id)->where('acceptance_status', 'Accepted')->exists();
    }


    public function create(Request $request)
    {

        // Insert group
        $group = new Group();

        $this->authorize('create', $group); // TODO : FAZER

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

        $this->authorize('delete', $group); // TODO
        $group->delete();

        return $group;
    }



    public function addGroupOwner($idUser, $idGroup)
    {

        $owner = new Owner();

        $this->authorize('create', $owner); // TODO

        $owner->id_user = $idUser;
        $owner->id_group = $idGroup;

        $owner->save();

        return $owner;
    }


    public function removeGroupOwner($id)
    {
        $owner = Owner::find($id);
        $this->authorize('delete', $owner); // TODO
        $owner->delete();

        return $owner;
    }

}
