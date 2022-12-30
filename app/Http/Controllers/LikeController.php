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
    public function toggle(Request $request) // TODO... mesma coisa do commentLikeController
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("Hello from 1");

        validator($request->route()->parameters(), [
            'id_post' => 'integer|exists:post,id',
        ])->validate();

        $out->writeln("Hello from 2");

        $user = $request->input('id_user');
        $post = $request->input('id_post');

        $out->writeln("Hello from 3");

        $like = DB::table('like_post')
            ->where('id_user', $user)
            ->where('id_post', $post)
            ->first();

        $out->writeln("Hello from 4");

        //if (!$request->user()->can('view', Post::find($post))) // POLICY ... the user must be able to see the post to like it
            //return response()->json(["You are not allowed to like this resourse" => 301]);

        $out->writeln("Hello from 5");

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
