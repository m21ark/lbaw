<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Group;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function show()
    {
        if (!Auth::check())
            return redirect('403');

        $this->authorize('view', Auth::user()->isAdmin); // WORKING

        return view('pages.admin', ['reports' => Report::get()]);
    }

    public function showStatistics()
    {

        $this->authorize('view', Auth::user()->isAdmin); // WORKING
        
        $statistics = [
            'posts_c' => Post::count(),
            'users_c' => User::count(),
            'groups_c' => Group::count(),
            'comments_c' => Comment::count(),
            'reports_c' => Report::count(),
            'likes_c' => Like::count() + CommentLike::count()
        ];

        return view('pages.admin_stats', ['statistics' => $statistics, 'reports' => Report::get()]);
    }

    public function usersReportesPending(Request $request)
    {

        $query_string = $request->route('query_string');

        $this->authorize('view', Auth::user()->isAdmin); // WORKING

        if ($query_string === '*') $query_string = '';


        $users_reported_post = Report::where('id_post', '<>', NULL)
            ->where('user_report.decision', 'Pendent')
            ->join('post', 'post.id', '=', 'user_report.id_post')
            ->join('user', 'user.id', '=', 'post.id_poster')
            ->where('username', 'LIKE', '%' . $query_string . '%')
            ->select('user.id', 'user.username', 'user.photo', DB::raw('count(user_report.id) as report_count'))
            ->groupBy('user.id');

        $users_reported_comments = Report::where('id_comment', '<>', NULL)
            ->where('user_report.decision', 'Pendent')
            ->join('comment', 'comment.id', '=', 'user_report.id_comment')
            ->join('user', 'user.id', '=', 'comment.id_commenter')
            ->where('username', 'LIKE', '%' . $query_string . '%')
            ->select('user.id', 'user.username', 'user.photo', DB::raw('count(user_report.id) as report_count'))
            ->groupBy('user.id');


        return $users_reported_post->union($users_reported_comments)
            ->orderBy('report_count')
            ->limit(10)
            ->get();
    }



    public function usersReportesPast(Request $request)
    {

        $this->authorize('view', Auth::user()->isAdmin); // WORKING

        $query_string = $request->route('query_string');

        if ($query_string === '*') $query_string = '';

        $users_reported_post = Report::where('id_post', '<>', NULL)
            ->whereIn('user_report.decision', ['Accepted', 'Rejected'])
            ->join('post', 'post.id', '=', 'user_report.id_post')
            ->join('user', 'user.id', '=', 'post.id_poster')
            ->where('username', 'LIKE', '%' . $query_string . '%')
            ->select('user.id', 'user.username', 'user.photo', 'user.ban_date', 'user_report.decision', 'user_report.decision_date')
            ->groupBy('user.id', 'user_report.decision', 'user_report.decision_date');


        $users_reported_comments = Report::where('id_comment', '<>', NULL)
            ->whereIn('user_report.decision', ['Accepted', 'Rejected'])
            ->join('comment', 'comment.id', '=', 'user_report.id_post')
            ->join('user', 'user.id', '=', 'comment.id_commenter')
            ->where('username', 'LIKE', '%' . $query_string . '%')
            ->select('user.id', 'user.username', 'user.photo', 'user.ban_date', 'user_report.decision', 'user_report.decision_date')
            ->groupBy('user.id', 'user_report.decision', 'user_report.decision', 'user_report.decision_date');


        return $users_reported_post->union($users_reported_comments)
            ->orderBy('decision_date', 'desc')
            ->limit(10)
            ->get();
    }
}
