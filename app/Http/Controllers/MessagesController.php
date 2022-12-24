<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;
use App\Events\NewNotification;

class MessagesController extends Controller
{

    public function create($id, Request $request)
    {
        if (!Auth::check())
            return response()->json(['failure' => 403]);

        if ($request->text === null)
            return response()->json(['Text cannot be null' => 400]);

        $receiver = User::find($id);

        if ($receiver->visibility !== true) {
            $this->authorize('delete', $receiver); // POLICY ... This means they are friends
        }

        $sms = new Message();
        $sms->text = $request->text;
        $sms->id_sender = Auth::user()->id;
        $sms->id_receiver = intval($id);

        $sms->save();

        event(new NewNotification(
            intval($id),
            'message',
            Auth::user(),
            $sms
        ));

        return response()->json(['Successfully created' => 201]);
    }


    public function show($sender_username = null)
    {

        if (!Auth::check())
            return redirect('403');

        $user = Auth::user();

        if ($sender_username !== null) 
            $receiver = User::where('username', '=', $sender_username)->firstOrFail();

        $messages = $user->messages()
            ->filter(function ($item) use ($sender_username) {
                return $item->sender->username === $sender_username
                    || $item->receiver->username === $sender_username;
            });

        if ($sender_username !== null && $receiver->visibility !== true && count($messages) == 0) {
                $this->authorize('delete', $receiver); // POLICY ... This means they are friends (see BR)
        }

        $sender = User::where('username', '=', $sender_username)->first();

        return view('pages.messages', ['user' => $user, 'messages' => $messages, 'sender' => $sender]);
    }
}
