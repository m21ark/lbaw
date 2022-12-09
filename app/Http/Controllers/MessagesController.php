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

        // TODO POLICY ... tem de se verificar se são amigos ... se bem que isto já está implementado na bd

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


    public function show($sender_username)
    {

        if (!Auth::check())
            return redirect('403');

        $user = Auth::user();

        $messages = $user->messages()
            ->filter(function ($item) use ($sender_username) {
                return $item->sender->username === $sender_username
                    || $item->receiver->username === $sender_username;
            });

        $sender = User::where('username', '=', $sender_username)->first();



        return view('pages.messages', ['user' => $user, 'messages' => $messages, 'sender' => $sender]);
    }
}
