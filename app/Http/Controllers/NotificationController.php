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


        //No need for POLICY as We just want to check if the user is logged in ... middleware

        $user = [];
        foreach (Auth::user()->notifications as $not) {
            $user[] = $not->sender;
        }

        $nots = Auth::user()->notifications->filter(function ($obj) {
            return $obj->seen == False;
        })->values();

        return json_encode($nots);
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


    public function markAllAsSeen()
    {
        if (!Auth::check())
            return response()->json(['You need to authenticate to use this endpoint' => 403]);

        //No need for POLICY as We just want to check if the user is logged in ... middleware

        foreach (Auth::user()->notifications as $not) {
            $not->seen = True;
            $not->save();
        }

        return response()->json(['The notifications were marked as seen' => 200]);
    }
}
