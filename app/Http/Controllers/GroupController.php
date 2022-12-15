<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupJoinRequest;
use App\Models\Owner;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function show($name)
    {
        $group = Group::where('name', $name)->first();

        if ($group == null) {
            //No group with that name so we return to the home page
            return redirect()->route('home');
        }

        if (!$group->visibility) {
            $this->authorize('view', $group);
        }
        return view('pages.group', ['group' => $group, 'in_group' => $this->userInGroup(Auth::user(), $group), 'user' => Auth::user()]);
    }

    public static function userInGroup(User $user1, Group $group)
    {
        return DB::table('group_join_request')
            ->where('id_user', $user1->id)
            ->where('id_group', $group->id)->where('acceptance_status', 'Accepted')->exists()
            || $group->owners()->where('id_user', $user1->id)->exists();
    }


    public function create(Request $request)
    {
        if ($request->user() === null) {
            return response()->json(['failure' => 401]);
        }

        $group = new Group();

        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->visibility = $request->input('visibility');
        // TODO : ADD PROFILE IMAGE

        // Insert Group Owner
        $ownerId = Auth::user()->id;

        $group->save();

        $owner = $this->addGroupOwner($ownerId, $group->id);

        $group->owners()->save($owner);


        return $group;
    }


    public function delete($name)
    {   // TODO : ESTA POLICY DPS DE TRATAR DO TRIGGER

        $group = Group::where('name', $name)->first();
        //$this->authorize('delete', $group); // TODO
        // $group->delete(); NAO DA POR CAUSA DOS TRIGGERS :(

        return $group;
    }

    public function edit(Request $request)
    {

        DB::beginTransaction();
        $id_group = $request->input('id_group');
        $group = Group::find($id_group);

        $this->authorize('update', $group);

        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->visibility = $request->input('visibility') == 'on' ? true : false;

        if ($request->hasFile('photo')) {

            $group->photo = 'group/' . strval($group->id) . '.jpg';

            try {
                $request->file('photo')->move(public_path('group/'), $group->id . '.jpg');
            } catch (Exception $e) {
                DB::rollBack();
            }
        }
        DB::commit();

        $group->save();

        return $group;
    }



    public function addGroupOwner($idUser, $idGroup)
    {
        /*
            N DEVE ESTAR AQUI
        */

        $owner = new Owner();

        //$this->authorize('create', $owner); // TODO

        $owner->id_user = $idUser;
        $owner->id_group = $idGroup;

        return $owner;
    }


    public function removeGroupOwner($id)
    {
        /*
            N DEVE ESTAR AQUI e talvez nem faça sentido
        */

        $owner = Owner::find($id);
        //$this->authorize('delete', $owner); // TODO
        $owner->delete();

        return $owner;
    }


    public function addGroupMember($idUser, $idGroup)
    {
        /*
            N DEVE ESTAR AQUI
        */

        $request = new GroupJoinRequest();

        //$this->authorize('create', $request);

        $request->id_user = $idUser; // TODO
        $request->id_group = $idGroup;
        $request->acceptance_status = 'Pending';

        $request->save();

        return $request;
    }


    public function removeGroupMember($idGroup, $idUser)
    {
        /*
            N DEVE ESTAR AQUI
        */

        // TODO Policy

        DB::table('group_join_request')
            ->where('id_group', $idGroup)->where('id_user', $idUser)
            ->update(['acceptance_status' => 'Rejected']);
    }
}
