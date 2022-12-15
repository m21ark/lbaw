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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GroupJoinRequest  $groupJoinRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(GroupJoinRequest $groupJoinRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GroupJoinRequest  $groupJoinRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupJoinRequest $groupJoinRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GroupJoinRequest  $groupJoinRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupJoinRequest $groupJoinRequest)
    {
        //
    }
}
