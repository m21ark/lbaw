<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        $user = $request->input('id_user');
        $post = $request->input('id_post');

        $like = DB::table('like_post')
            ->where('id_user', $user)
            ->where('id_post', $post)
            ->first();

        $request->user()->can('view', Post::find($post)); // POLICY

        if ($like == null) {

            $like = new Like();
            $like->id_user = $user;
            $like->id_post = $post;
            $like->save();

            event(new NewNotification(
                intval($like->post->owner->id),
                'Like',
                Auth::user(),
                $like->toArray()
            ));
        } else {

            DB::table('like_post')
                ->where('id_user', $user)
                ->where('id_post', $post)
                ->delete();
        }

        return response()->json(["The comment was liked with success" => 200]);
    }
}
