<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Pusher\Pusher;

class PusherController extends Controller
{
     /**
     * Authenticates logged-in user in the Pusher JS app
     * For private channels
     */
    public function pusherAuth(Request $request)
    {
        $socketId = $request->input('socket_id');

        $channel = $request->input('channel_name');

        $presenceData = array('user_id' => Auth::user()->id);

        $pusher = new Pusher('c827040c068ce8231c02', 'b1c2a48a3bbfb2df4f10', '1515597'); 
     
        $auth = $pusher->authorizePresenceChannel($channel, $socketId, Auth::user()->id, $presenceData);

        return $auth;
    }
}