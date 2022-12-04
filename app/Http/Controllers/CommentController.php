<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\NewNotification;

class CommentController extends Controller
{
    public function create($id_post, Request $request)
    {
        // $this->authorize('create', Comment::class);

        $text = $request->input('text');

        preg_match_all('/(?<=@)\w+/m', $text, $matches);

        if (sizeof($matches[0]) === 0) {

            $comment = new Comment();
            $comment->id_post = $id_post;
            $comment->id_commenter = Auth::user()->id;
            $comment->text = $text;
            $comment->save();

            event(new NewNotification(intval($comment->post->owner->id), 'Comment', Auth::user()
            , $comment));

        } else if (sizeof($matches[0]) === 1) {

            // TODO adicionar notificação de erro
            // TODO guardar o @ do user no texto é má ideia
            // pq se o user mudar de nome deixa de fazer sentido

            $username = $matches[0][0];

            $aux = DB::table('comment')
                ->join('user', 'user.id', '=', 'comment.id_commenter')
                ->join('post', 'post.id', '=', 'comment.id_post')
                ->where('user.username', $username)
                ->where('post.id', $id_post)
                ->get('comment.id')->first();

            if ($aux) { // TODO: TRANSACTION
                $comment = new Comment();
                $comment->id_post = $id_post;
                $comment->id_commenter = Auth::user()->id;
                $comment->text = $text;
                $comment->save();
                
                event(new NewNotification(intval($comment->post->owner->id), 'Comment', Auth::user()
                 , $comment));
                
                $comment->id_parent = $aux->id;
                $comment->save();
                
                $parent_comment = Comment::find($comment->id_parent);
                event(new NewNotification(intval($parent_comment->id_commenter), 'Comment', Auth::user()
                 , $comment));
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
