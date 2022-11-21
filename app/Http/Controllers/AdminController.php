<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function show()
    {
        return view('pages.admin');
    }


    public function usersReportesPending(Request $request) {

        $query_string = $request->route('query_string');

        if ($query_string === '*') $query_string = '';

        $users_reported_post = Report::
        where('id_post', '<>', NULL) 
        ->where('user_report.decision', 'Pendent')
        ->join('post', 'post.id', '=', 'user_report.id_post')
        ->join('user', 'user.id', '=', 'post.id_poster')
        ->where('username', 'LIKE', '%'.$query_string.'%')
        ->select('user.id', 'user.username', 'user.photo', DB::raw('count(user_report.id) as report_count'))
        ->groupBy('user.id');

        $users_reported_comments = Report::
        where('id_comment', '<>', NULL)
        ->where('user_report.decision', 'Pendent')
        ->join('comment', 'comment.id', '=', 'user_report.id_comment')
        ->join('user', 'user.id', '=', 'comment.id_commenter')
        ->where('username', 'LIKE', '%'.$query_string.'%')
        ->select('user.id', 'user.username', 'user.photo', DB::raw('count(user_report.id) as report_count'))
        ->groupBy('user.id');


        return $users_reported_post->union($users_reported_comments)
        ->orderBy('report_count')
        ->limit(5)
        ->get();
    }



    public function usersReportesPast(Request $request) {

        return [];
    }
}
