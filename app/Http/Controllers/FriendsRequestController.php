<?php

namespace App\Http\Controllers;

use App\Models\FriendsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsRequestController extends Controller
{

    public function show()
    {
        if (!Auth::check())
            return redirect()->route('home');

        return view('pages.friends_requests', ['user' => Auth::user()]);
    }


    public function accept($id_sender)
    {
        return update_request($id_sender, "Rejected");
    }

    public function reject($id_sender)
    {
        return update_request($id_sender, "Rejected");
    }

    public function update_request($id_sender, $new_state)
    {
        validator($request->route()->parameters(), [
            'id_sender' => 'required|exists:users,id',
        ])->validate();

        if (!Auth::check())
        return response()->json(['You need to authenticate to use this endpoint' => 403]);

        // TODO :ADD policy
        $request = FriendsRequest::where('id_user_sender', '=', $id_sender)
        ->where('id_user_receiver', '=', Auth:user()->id)
        ->first();
        
        $request->acceptance_status = $new_state;
        $request->save();

        return response()->json(['The request was ' . $new_state . " with success" => 200]);
    }
}
