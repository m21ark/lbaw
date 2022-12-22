<?php

namespace App\Http\Controllers;

use App\Models\FriendsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewNotification;
use App\Models\User;

class FriendsRequestController extends Controller
{

    public function show()
    {
        if (!Auth::check())
            return redirect()->route('home');
        
        // NO need to call policy here because the view is only for the user

        return view('pages.friends_requests', ['user' => Auth::user(), 'requests' => Auth::user()->pendentFriendsRequests, 'isrequests' => true]);
    }

    public function friends($username)
    {
        if (!Auth::check())
            return redirect()->route('home');

        $user = User::where('username', '=', $username)->firstOrFail();

        if ($user === null)
            return redirect()->route('home');

        // CHECK authserviceproviders to understand from where this policy comes
        $this->authorize('view', $user);

        return view('pages.friends_requests', ['user' => $user, 'requests' => $user->friends(), 'isrequests' => false]);
    }

    public function send($id_rcv, Request $request)
    {
        if (!Auth::check())
            return response()->json(['You need to authenticate to use this endpoint' => 403]);

        // N é preciso Policy uma vez que basta estar com session

        validator($request->route()->parameters(), [
            'id_rcv' => 'required|exists:user,id',
        ])->validate();

        $frequest = new FriendsRequest();
        $frequest->id_user_sender = Auth::user()->id;
        $frequest->id_user_receiver = $id_rcv;
        $frequest->save();

        event(new NewNotification(
            intval($id_rcv),
            'FriendRequest',
            Auth::user(),
            $frequest->toArray()
        ));

        return response()->json(['The request was sent' => 200]);
    }

    public function accept($id_sender, Request $request)
    {
        // Policy bellow
        return $this->update_request($id_sender, "Accepted", $request);
    }

    public function reject($id_sender, Request $request)
    {   
        // Policy bellow
        return $this->update_request($id_sender, "Rejected", $request);
    }

    public function update_request($id_sender, $new_state, Request $request)
    {
        validator($request->route()->parameters(), [
            'id_sender' => 'required|exists:user,id',
        ])->validate();

        if (!Auth::check())
            return response()->json(['You need to authenticate to use this endpoint' => 403]);

        $frequest = FriendsRequest::where('id_user_sender', '=', $id_sender)
            ->where('id_user_receiver', '=', Auth::user()->id);

        // POLICY
        $this->authorize('update', $frequest->firstOrFail()); // WORKING

        $frequest->update(['acceptance_status' => $new_state]);

        return response()->json(['The request was ' . $new_state . " with success" => 200]);
    }

    public function delete($id, Request $request)
    {
        validator($request->route()->parameters(), [
            'id' => 'required|exists:user,id',
        ])->validate();

        if (!Auth::check())
            return response()->json(['You need to authenticate to use this endpoint' => 403]);

        $this->authorize('create'); 

        FriendsRequest::where('id_user_sender', '=', Auth::user()->id)
            ->where('id_user_receiver', '=', $id)
            ->delete();

        FriendsRequest::where('id_user_sender', '=', $id)
            ->where('id_user_receiver', '=', Auth::user()->id)
            ->delete();

        return response()->json(['The request was deleted with success' => 200]);
    }
}
