<?php

namespace App\Http\Controllers;

use App\Models\GroupJoinRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GroupJoinRequestController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

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

        // TODO ::: VER POLICY... tem de ser group owner

        $group = Group::where('name', $group_name)->first();

        return view('pages.group_requests', ['requests' => $group->groupJoinRequests]);
    }

    public function accept($id_sender, $group_name, Request $request)
    {
        return $this->update_request($id_sender, $group_name, "Accepted", $group_name, $request);
    }

    public function reject($id_sender, $group_name, Request $request)
    {
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

        // TODO :ADD policy
        $group = Group::where('name', $group_name)->first();
        $frequest = GroupJoinRequest::where('id_user', '=', $id_sender)
            ->where('id_group', '=', $group->name)
            ->update(['acceptance_status' => $new_state]);

        return response()->json(['The request was ' . $new_state . " with success" => 200]);
    }
}
