<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->first();

        if ($user == null) {
            //No user with that name so we return to the home page
            return redirect()->route('home');
        }

        $statistics = [
            'post_num' => $user->posts->count(),
            'comment_num' => $user->comments->count(),
            'like_comment_num' => $user->like_in_comments->count(),
            'like_post_num' => $user->like_in_post->count(),
            'group_num' => $user->groupsMember->count(),
            'friends_num' => $user->friends()->count(),
        ];

        $friends = Auth::check() ? PostController::areFriends(Auth::user(), $user) : null;
        return view('pages.profile', ['user' => $user, 'statistics' => $statistics, 'friends' => $friends]);
    }

    public function edit(Request $request)
    {

        $user = Auth::user();

        if ($user == null) {
            return redirect()->route('home');
        }

        DB::beginTransaction();
        $user->username = $request->input('username') ?? $user->username; // TODO: Check if username is unique
        $user->birthdate = $request->input('bdate') ?? $user->birthdate;
        $user->visibility = $request->input('visibility') == 'on' ? true : false;
        $user->email = $request->input('email') ?? $user->email; // TODO: Check if email is unique
        $user->bio = $request->input('bio') ?? $user->bio;

        // TODO : ADD PASSWORD
        // TODO: EDIT ALSO USER INTERESTS

        if ($request->hasFile('photo')) {

            $user->photo = 'user/' . strval($user->id) . '.jpg';

            try {
                $request->file('photo')->move(public_path('user/'), $user->id . '.jpg');
            } catch (Exception $e) {
                DB::rollBack();
            }
        }
        $user->save();

        DB::commit();

        return redirect()->route('profile', $user->username);
    }


    public function delete($username)
    {

        // Mesmo problema que em grupos... triggers impedem de apagar
        // aqui julgo que é a cena de n poder deixar grupos sem outros owners
        $user = User::where('username', $username)->first();

        // TODO ::: POLICY
        
        $user->username = "deleted_User". $user->id;
        $user->email = "deleted_email". $user->id . "@d.com";

        $user->save();
        
        Auth::logout();

        return redirect()->route('home');
    }
}
