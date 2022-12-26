<?php

namespace App\Http\Controllers;


use App\Models\Group;
use App\Models\GroupJoinRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GroupJoinRequestController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GroupJoinRequest  $groupJoinRequest
     * @return \Illuminate\Http\Response
     */
    public function show($group_name)
    {
        if (!Auth::check())
            return redirect()->route('home');

        $group = Group::where('name', $group_name)->first();
        $this->authorize('update', $group); // GROUP POLICY ... working

        return view('pages.group_requests', ['requests' => $group->groupJoinRequests]);
    }

    public function accept($group_name, $id_sender, Request $request)
    {
        // POLICY IS IN update_request FUNCTION
        return $this->update_request($id_sender, $group_name, "Accepted", $request);
    }

    public function reject($group_name, $id_sender,  Request $request)
    {
        // POLICY IS IN update_request FUNCTION
        return $this->update_request($id_sender, $group_name, "Rejected", $request);
    }

    public function update_request($id_sender, $group_name, $new_state, Request $request)
    {
        validator($request->route()->parameters(), [
            'id_sender' => 'required|exists:user,id',
            'group_name' => 'required|exists:group,name',
        ])->validate();

        if (!Auth::check())
            return response()->json(['You need to authenticate to use this endpoint' => 403]);

        $group = Group::where('name', $group_name)->first();
        $frequest = GroupJoinRequest::where('id_user', '=', $id_sender)
            ->where('id_group', '=', $group->id);

        $this->authorize('update', $frequest->firstOrFail()); // POLICY ... working

        $frequest->update(['acceptance_status' => $new_state]);

        return response()->json(['The request was ' . $new_state . " with success" => 200]);
    }

    public function send($id, Request $resquest)
    {
        if (!Auth::check())
            return response()->json(['You need to authenticate to use this endpoint' => 403]);

        Auth::user()->can('create'); // POLICY ... WORKING

        $frequest = new GroupJoinRequest();
        $frequest->id_user = Auth::user()->id;
        $frequest->id_group = $id;
        $frequest->save();

        return response()->json(['The request was sent' => 200]);
    }

    public function delete($id, Request $request)
    {
        if (!Auth::check())
            return response()->json(['You need to authenticate to use this endpoint' => 403]);

        validator($request->route()->parameters(), [
            'id' => 'required|exists:group,id',
        ])->validate();

        $r = GroupJoinRequest::where('id_user', '=', Auth::user()->id)
            ->where('id_group', '=', $id);

        $this->authorize('delete', $r->firstOrFail()); // POLICY ... WORKING

        $r->delete();

        return response()->json(['The request was deleted with success' => 200]);
    }
}
