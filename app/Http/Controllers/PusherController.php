<?php

namespace App\Http\Controllers;

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

        // $user = auth()->user();
        // $socket_id = $request['socket_id'];
        // $channel_name =$request['channel_name'];
        // $key = getenv('PUSHER_APP_KEY');
        // $secret = getenv('PUSHER_APP_SECRET');
        // $app_id = getenv('PUSHER_APP_ID');
// 
        // if ($user) {
     // 
        //     $pusher = new Pusher($key, $secret, $app_id);
        //     $auth = $pusher->authorizeChannel($channel_name, $socket_id);
// 
        //     return response($auth, 200);
// 
        // } else {
        //     header('', true, 403);
        //     echo "Forbidden";
        //     return;
        // }
        //Route::post('/broadcasting/auth', function (Request $request) {
        //    if(Auth::check())
        //        {
        //            //Fetch User Object
        //            $user =  Auth::user();
        //            //Presence Channel information. Usually contains personal user information.
        //            //See: https://pusher.com/docs/client_api_guide/client_presence_channels
        //            $presence_data = array('name' => $user->first_name." ".$user->last_name);
        //            //Registers users' presence channel.
        //            return $this->pusher->presence_auth($request->input('channel_name'), $request->input('socket_id'), $user->id, $presence_data);       
        //        }
        //        else
        //        {
        //            return Response::make('Forbidden',403);
        //        }
        //});

        //$socketId = $request->input('socket_id');
        //$channel = $request->input('channel_name');
        //$presenceData = array('user_id' => Auth::user()->id);
//
        //$pusher = new Pusher(Config::get('pusher.app_key'), Config::get('pusher.app_secret'), Config::get('pusher.app_id'));
        //$auth = $pusher->authorizePresenceChannel($channel, $socketId, Auth::user()->id, $presenceData);
//
        //return response()->json($auth);
    }
}