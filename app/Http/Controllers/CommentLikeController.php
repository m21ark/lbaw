<?php

namespace App\Http\Controllers;

use App\Models\CommentLike;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Auth;


class CommentLikeController extends Controller
{
    public function toggle(Request $request)
    {

        $request->validate([
            'id_comment' => 'string|exists:comment,id',
        ]);

        $user = $request->input('id_user');
        $comment = $request->input('id_comment');

        $commentModel = Comment::find($comment);


        // The user must be able to see it to comment it ...
        if (!$request->user()->can('view', $commentModel))
            return response()->json(["You are not allowed to like this resourse" => 301]);

        $like = CommentLike::where('id_user', $user)
            ->where('id_comment', $comment)
            ->first();

        if ($like === null) {
            $like = new CommentLike();
            $like->id_user = $user;
            $like->id_comment = $comment;
            $like->save();

            event(new NewNotification(
                intval($like->comment->poster->id),
                'Like',
                Auth::user(),
                $like->toArray()
            ));
        } else {
            $like = CommentLike::where('id_user', $user)
                ->where('id_comment', $comment)
                ->delete();
        }

        return response()->json(["The comment was liked with success" => 200]);
    }
}
