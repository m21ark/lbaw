<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function get() 
    {
        if (!Auth::check())
            return response()->json(['You need to authenticate to use this endpoint' => 403]);

        return json_encode(Auth::user()->notifications);
    }


    public function markAsSeen($id) 
    {
        if (!Auth::check())
            return response()->json(['You need to authenticate to use this endpoint' => 403]);

        $not = Notification::find($id);
            
        $this->authorize('update', $not);

        $not->seen = True;

        $not->save();

        return response()->json(['The notification was marked as seen' => 200]);
    }
}
