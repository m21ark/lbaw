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

        $out = new \Symfony\Component\Console\Output\ConsoleOutput();

        $socketId = $request->input('socket_id');

        $channel = $request->input('channel_name');

        $presenceData = array('user_id' => Auth::user()->id);

        $pusher = new Pusher(1515597, 'c827040c068ce8231c02', 'b1c2a48a3bbfb2df4f10');
     
        $out->writeln("Hello from Tereeeeeminal");

        $auth = $pusher->authorizePresenceChannel($channel, $socketId, Auth::user()->id, $presenceData);
        $out->writeln("Hello from Tereeeeeminal");

        return response()->json($auth);
        //return response()->json(['auth' => "DLDKKK", 'suc' => 200, 'channel_data' => 'dDdddd']);

    }
}