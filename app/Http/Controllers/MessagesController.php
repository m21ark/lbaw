<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MessagesController extends Controller
{
    public function show($sender_username)
    {

        if (!Auth::check())
            return redirect('403');

        $user = Auth::user();

        $messages = $user->messages()
        ->filter(function ($item) use ($sender_username){
            return $item->sender->username === $sender_username
            || $item->receiver->username === $sender_username;
        });

        $sender = User::where('username', '=', $sender_username)->first();

        

        return view('pages.messages', ['user' => $user, 'messages' => $messages, 'sender' => $sender]);
    }
}
