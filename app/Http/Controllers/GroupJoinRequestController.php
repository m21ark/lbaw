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

        // TODO ::: VER POLICY... tem de ser group owner

        $group = Group::where('name', $group_name)->first();

        return view('pages.group_requests', ['requests' => $group->groupJoinRequests]);
    }

    public function accept( $group_name, $id_sender, Request $request)
    {
        return $this->update_request($id_sender, $group_name, "Accepted", $request);
    }

    public function reject($group_name,$id_sender,  Request $request)
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
            ->where('id_group', '=', $group->id)
            ->update(['acceptance_status' => $new_state]);
        
        return response()->json(['The request was ' . $new_state . " with success" => 200]);
    }

    public function send($id, Request $resquest) 
    {
        if (!Auth::check())
            return response()->json(['You need to authenticate to use this endpoint' => 403]);

        // TODO : E PRECISO OUTRA POLICIE ? no caso de se já ter enviado um pedido

        // PK ??? 
        //validator($request->route()->parameters(), [
        //    'id' => 'required|exists:group,id',
        //])->validate();

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

        // TODO :ADD policy
        
        GroupJoinRequest::where('id_user', '=', Auth::user()->id)
            ->where('id_group', '=', $id)
            ->delete();
            
        return response()->json(['The request was deleted with success' => 200]);
    }
}
