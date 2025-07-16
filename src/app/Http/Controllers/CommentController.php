<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\NewNotification;
use App\Models\Post;

class CommentController extends Controller
{
    public function create($id_post, Request $request)
    {

        $request->validate([
            'text' => 'string|min:0|max:2000' // strip tags to sanitize input // SEE BELLOW
        ]);

        $text = strip_tags($request->input('text'));

        $post = Post::find($id_post);
        $this->authorize('create', $post); // funciona

        preg_match_all('/(?<=@)\w+/m', $text, $matches);

        if (sizeof($matches[0]) === 0) {

            $comment = new Comment();
            $comment->id_post = $id_post;
            $comment->id_commenter = Auth::user()->id;
            $comment->text = $text;
            $comment->save();

            event(new NewNotification(
                intval($comment->post->owner->id),
                'Comment',
                Auth::user(),
                $comment
            ));

            return view('partials.comment_item', ['comment' => $comment])->render();
        } else if (sizeof($matches[0]) === 1) {

            $username = $matches[0][0];

            $aux = DB::table('comment')
                ->join('user', 'user.id', '=', 'comment.id_commenter')
                ->join('post', 'post.id', '=', 'comment.id_post')
                ->where('user.username', $username)
                ->where('post.id', $id_post)
                ->get('comment.id')->first();

            if ($aux) { 
                $comment = new Comment();
                $comment->id_post = $id_post;
                $comment->id_commenter = Auth::user()->id;
                $comment->text = $text;
                $comment->save();

                // notificate the owner and the comment owner
                event(new NewNotification(
                    intval($comment->post->owner->id),
                    'Comment',
                    Auth::user(),
                    $comment
                ));

                $comment->id_parent = $aux->id;
                $comment->save();

                $parent_comment = Comment::find($comment->id_parent);
                event(new NewNotification(
                    intval($parent_comment->id_commenter),
                    'Comment',
                    Auth::user(),
                    $comment
                ));

                return view('partials.comment_item', ['comment' => $comment])->render();
            }
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'id_comment' => 'string|exists:comment,id',
            'text' => 'string|min:0|max:2000' // strip tags to sanitize inout // SEE BELLOW
        ]);

        $comment = Comment::find($request->input('id_comment'));
        $this->authorize('update', $comment);
        $comment->text = strip_tags($request->input('text'));
        $comment->save();
    }

    public function delete(Request $request, $id_comment)
    {
        $request->validate([
            'id_comment' => 'string|exists:comment,id', 
        ]);

        $comment = Comment::find($id_comment);
        $this->authorize('delete', $comment);
        $comment->delete();
    }
}
