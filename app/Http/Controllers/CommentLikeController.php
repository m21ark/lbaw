<?php

namespace App\Http\Controllers;

use App\Models\CommentLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Auth;


class CommentLikeController extends Controller
{
    public function toggle(Request $request)
    {
        // TODO : N POSSO FAZER ESTA ENQUANTO O COMMENT LIKE N FUNCIONAR
        //$this->authorize('create', Auth::user());

        $user = $request->input('id_user');
        $comment = $request->input('id_comment');

        $like = CommentLike::where('id_user', $user)
            ->where('id_comment', $comment)
            ->first();

        if ($like === null) {
            $like = new CommentLike();
            $like->id_user = $user;
            $like->id_comment = $comment;
            $like->save();

            event(new NewNotification(intval($like->comment->poster->id), 'Like', Auth::user()
            , $like->toArray()));

        } else {
            $like = CommentLike::where('id_user', $user)
                ->where('id_comment', $comment)
                ->delete();
        }

        return $like;
    }
}
