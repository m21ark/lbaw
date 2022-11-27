<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function create($id_post, Request $request)
    {
        // $this->authorize('create', Comment::class);
        DB::table('comment')->insert([
            'id_post' => $id_post,
            'id_commenter' => Auth::user()->id,
            'text' => $request->input('text'),
        ]);
    }

    public function delete($id_comment)
    {
        $comment = Comment::find($id_comment);
        $this->authorize('delete', $comment);
        $comment->delete();
    }

    public function edit($id_comment, Request $request)
    {
        $comment = Comment::find($id_comment);
        $this->authorize('edit', $comment);
        $comment->text = $request->input('text');
        $comment->save();
    }
}
