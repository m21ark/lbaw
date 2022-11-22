<?php

namespace App\Http\Controllers;

use App\Models\CommentLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentLikeController extends Controller
{
    public function toggle(Request $request)
    {
        $user = $request->input('id_user');
        $comment = $request->input('id_comment');

        $like = DB::table('like_comment')
            ->where('id_user', $user)
            ->where('id_comment', $comment)
            ->first();

        //$this->authorize('create', Auth::user());

        if ($like == null) {

            $like = new CommentLike();
            $like->id_user = $user;
            $like->id_comment = $comment;
            $like->save();
        } else {

            DB::table('like_comment')
                ->where('id_user', $user)
                ->where('id_comment', $comment)
                ->delete();
        }

        return $like;
    }
}
