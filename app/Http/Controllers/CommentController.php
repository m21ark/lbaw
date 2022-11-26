<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Client\Request;
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Group;
use App\Models\Image;
use App\Models\User;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\File;

class CommentController extends Controller
{
    public function create($id_post /* Request $request*/)
    {


         $out = new \Symfony\Component\Console\Output\ConsoleOutput();
       $out->writeln('here0');
        return null;
        /*
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln('here0');

        $comment = new Comment();
        $out->writeln('here1');

        // TODO
        // $this->authorize('create', $post);

        if ($request->input('id_user') !==  Auth::user()->id)
            return null;

            $out->writeln(('here2'));

        $comment->id_user = $request->input('id_user');

        $out->writeln(('here3'));

        $comment->text = $request->input('text');
        $comment->id_post = $request->input('id_post');

        $out->writeln(('here4'));

        // $comment->save();


        return $comment;
        */
    }
}
