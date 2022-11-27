<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function create($id_post, $text/* Request $request*/)
    {


        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln('here: ' . $id_post);

        $out->writeln('here0');


        DB::table('comment')->insert(
            [
                'text' => $text,
                'id_post' => $id_post,
                'id_commenter' => Auth::user()->id,
            ]
        );
    }
}
