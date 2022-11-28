<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function create($id_post, Request $request)
    {
        // $this->authorize('create', Comment::class);

        $text = $request->input('text');

        preg_match_all('/(?<=@)\w+/m', $text, $matches);

        if (sizeof($matches[0]) === 0) {
            DB::table('comment')->insert([
                'id_post' => $id_post,
                'id_commenter' => Auth::user()->id,
                'text' => $text,
            ]);
        } else if (sizeof($matches[0]) === 1) {

            $username = $matches[0][0];

            $aux = DB::table('comment')
                ->join('user', 'user.id', '=', 'comment.id_commenter')
                ->join('post', 'post.id', '=', 'comment.id_post')
                ->where('user.username', $username)
                ->where('post.id', $id_post)->get('comment.id')->first();

            if ($aux) {
                DB::table('comment')->insert([
                    'id_post' => $id_post,
                    'id_commenter' => Auth::user()->id,
                    'text' => $text,
                    'id_parent' => $aux->id,
                ]);
            }
        }
    }

    public function edit(Request $request)
    {
        $comment = Comment::find($request->input('id_comment'));
        //$this->authorize('edit', $comment);
        $comment->text = $request->input('text');
        $comment->save();
    }

    public function delete($id_comment)
    {
        $comment = Comment::find($id_comment);
        //$this->authorize('delete', $comment);
        $comment->delete();
    }
}
