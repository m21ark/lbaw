<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupJoinRequest;
use App\Models\GroupTopic;
use App\Models\Owner;
use App\Models\Topic;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function show($name, Request $request)
    {

        $request->validate([
            'name' => 'string|exists:group,name',
        ]);

        $group = Group::where('name', $name)->first();

        if ($group == null) { // Never reached, if that happens then someone hacked validator
            //No group with that name so we return to the home page
            return redirect()->route('home');
        }

        $can_view_timeline = true;
        if (!$group->visibility) {
            $can_view_timeline = Auth::check() ? $request->user()->can('view', $group) : false; // POLICY
        }
        return view('pages.group', [
            'group' => $group,
            'in_group' => Auth::check() ? $this->userInGroup(Auth::user(), $group) : false,
            'user' => Auth::user(),
            'can_view_timeline' => $can_view_timeline,
        ]);
    }

    public function showEdit($name, Request $request)
    {
        $request->validate([
            'name' => 'string|exists:group,name',
        ]);

        $group = Group::where('name', $name)->first();

        if ($group == null) { // Never reached, if that happens then someone hacked validator
            //No group with that name so we return to the home page
            return redirect()->route('home');
        }

        if (!$request->user()->can('view', $group) && !Auth::user()->isAdmin) {
            return abort('403');
        }

        return view('pages.edit_group', ['group' => $group, 'user' => Auth::user()]);
    }

    public function showMemberList($name){
   

    $group = Group::where('name', $name)->first();

    if ($group == null) { // Never reached, if that happens then someone hacked validator
        //No group with that name so we return to the home page
        return redirect()->route('home');
    }

    return view('pages.member_list', [
        'group' => $group,
    ]);
    }
    public static function userInGroup(User $user1, Group $group)
    {   // METODO STATIC N PRECISA DE POLICY
        return DB::table('group_join_request')
            ->where('id_user', $user1->id)
            ->where('id_group', $group->id)->where('acceptance_status', 'Accepted')->exists()
            || $group->owners()->where('id_user', $user1->id)->exists();
    }


    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|string|min:1|max:100|unique:group,name',
            'description' => 'required|string|min:1|max:2000',
        ]);

        if ($request->user() === null && Group::where('name', '=', $request->input('name'))->firstOrFail() !== null) {
            return response()->json(['failure' => 401]);
        }

        // No need for policy ... the user just only has to have a authenticated account

        $group = new Group();
        $group->name = strip_tags($request->input('name'));
        $group->description = strip_tags($request->input('description'));
        $group->visibility = $request->input('visibility');

        // Insert Group Owner
        $ownerId = Auth::user()->id;

        $group->save();

        $owner = $this->addGroupOwner($ownerId, $group->id);
        $group->owners()->save($owner);

        $this->addGroupMember($ownerId, $group->id);

        $this->add_topics($request, $group);

        return response()->json(['The group was created with success' => 200]);
    }

    private function add_topics(Request $request, Group $group)
    {
        // THIS IS A FUNCTION AND DOES NOT NEED POLICY HERE... but in the callee function
        if ($request->input('tags') != null) {

            $topics = explode(' ', strip_tags($request->input('tags')));

            foreach ($topics as $topic) {

                $topic_ = Topic::where('topic', $topic)->first();
                if ($topic_ === null) {
                    $topic_ = new Topic();
                    $topic_->topic = $topic;
                    $topic_->save();
                }

                $group_topic = new GroupTopic();
                $group_topic->id_group = $group->id;
                $group_topic->id_topic = $topic_->id;
                $group_topic->save();
            }
        }
    }

    public function delete($name, Request $request)
    {

        $request->validate([
            'name' => 'string|min:1|max:100|exists:group,name', // NOTE exists group Name
        ]);

        $group = Group::where('name', $name)->first();
        $this->authorize('delete', $group); // POLICY....WORKING
        $group->delete();

        return response()->json(['The group was deleted with success' => 200]);
    }

    public function edit(Request $request)
    {

        $request->validate([
            'id_group' => 'integer|exists:group,id',
            'description' => 'required|string|min:1|max:2000',
        ]);

        DB::beginTransaction();
        $id_group = $request->input('id_group');
        $group = Group::find($id_group);

        $this->authorize('update', $group); // POLICY....WORKING

        $group->name = strip_tags($request->input('name'));
        $group->description = strip_tags($request->input('description'));
        $group->visibility = $request->input('visibility');

        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'image|mimes:jpg,jpeg,png,ico|min:1|max:50000', // VALIDATE IMAGE
            ]);

            $group->photo = 'group/' . strval($group->id) . '.jpg';

            try {
                $request->file('photo')->move(public_path('group/'), $group->id . '.jpg');
            } catch (Exception $e) {
                DB::rollBack();
            }
        }
        $this->edit_topics($request, $group);
        DB::commit();

        $group->save();

        return $group;
    }

    private function edit_topics(Request $request, Group $group)
    {
        //
        // The policy for this Function is in the function above
        //
        $group->topics()->delete();
        if ($request->input('tags') != null) {

            $topics = explode(' ', strip_tags($request->input('tags')));

            foreach ($topics as $topic) {

                $topic_ = Topic::where('topic', $topic)->first();

                if ($topic_ === null) {
                    $topic_ = new Topic();
                    $topic_->topic = $topic;
                    $topic_->save();
                }

                $group_topic = new GroupTopic();
                $group_topic->id_group = $group->id;
                $group_topic->id_topic = $topic_->id;
                $group_topic->save();
            }
        }
    }

    public function listGroups($username, Request $request)
    {

        $request->validate([
            'username' => 'string|exists:user,name',
        ]);

        $user = User::where('username', $username)->firstOrFail();

        if ($user === null)
            return redirect()->route('home');

        $this->authorize('view', $user); // USER policy ...WORKING

        return view('pages.group_list', ['user' => $user]);
    }



    public function addGroupMember($idUser, $idGroup)
    {
        /*
            This is not an api endpoint. It's called in another function that grantes the correct policy
            Hence this does not need a Policy
        */

        DB::table('group_join_request')->insert(
            ['id_group' => $idGroup, 'id_user' => $idUser, 'acceptance_status' => 'Accepted']
        );
    }

    public function addGroupOwner($idUser, $idGroup)
    {
        /*
            This is not an api endpoint. It's called in another function that grantes the correct policy
            Hence this does not need a Policy
        */

        $owner = new Owner();

        $owner->id_user = $idUser;
        $owner->id_group = $idGroup;

        return $owner;
    }

    public function removeGroupMember($idGroup, $idUser, Request $request)
    {

        $request->validate([
            'idGroup' => 'string|exists:group,id',
            'idUser' => 'string|exists:user,id',
        ]);

        $group = Group::find($idGroup);

        $this->authorize('delete', $group); // POLICY

        DB::table('group_join_request')
            ->where('id_group', $idGroup)->where('id_user', $idUser)
            ->update(['acceptance_status' => 'Rejected']);
    }

    public function newGroupOwner($idGroup, $idUser)
    {
        /*
            This is not an api endpoint. It's called in another function that grantes the correct policy
            Hence this does not need a Policy
        */
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
       
        $owner = new Owner();
        $owner->id_user = $idUser;
        $owner->id_group = $idGroup;
        $owner->save();
        return $owner;
    }
}


