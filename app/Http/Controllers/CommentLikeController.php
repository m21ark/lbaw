<?php

namespace App\Http\Controllers;

use App\Models\CommentLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentLikeController extends Controller
{
    public function toggle(Request $request)
    {
        // TODO : N POSSO FAZER ESTA ENQUANTO O COMMENT LIKE N FUNCIONAR
        //$this->authorize('create', Auth::user());

        $user = $request->input('id_user');
        $comment = $request->input('id_comment');

        $like = CommentLike::
            ->where('id_user', $user)
            ->where('id_comment', $comment)
            ->first();

        if ($like == null) {

            $like = new CommentLike();
            $like->id_user = $user;
            $like->id_comment = $comment;
            $like->save();
        } else {
            $like->delete();
        }

        return $like;
    }
}
