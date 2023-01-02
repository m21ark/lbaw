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
            return abort('403');

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
        $request->validate([
            'query_string' => 'string|min:0|max:1000' // THE is not such a big username name
        ]);

        $this->authorize('view', Auth::user()->isAdmin); // WORKING

        $users_reported = [];
        $query_string = $request->route('query_string');
        
        if ($query_string === '*') $query_string = '';
        
        $users_reported_post = Report::where('id_post', '<>', NULL)
            ->where('user_report.decision', 'Pendent')
            ->join('post', 'post.id', '=', 'user_report.id_post')
            ->join('user', 'user.id', '=', 'post.id_poster')
            ->where('username', 'ILIKE', '%' . $query_string . '%')
            ->select('user.id', 'user.username', 'user.photo', 'user_report.id as report_id');

        $users_reported_comments = Report::where('id_comment', '<>', NULL)
            ->where('user_report.decision', 'Pendent')
            ->join('comment', 'comment.id', '=', 'user_report.id_comment')
            ->join('user', 'user.id', '=', 'comment.id_commenter')
            ->where('username', 'ILIKE', '%' . $query_string . '%')
            ->select('user.id', 'user.username', 'user.photo', 'user_report.id as report_id');


        $users_reported = $users_reported_post->union($users_reported_comments);

        $users_reported = DB::table(DB::raw("({$users_reported->toSql()}) as sub"))
            ->mergeBindings($users_reported->getQuery())
            ->select('id', 'username', 'photo', DB::raw('count(report_id) as report_count'))
            ->groupBy('id', 'username', 'photo')
            ->orderBy('report_count', 'desc')
            ->limit(10)
            ->get();
        

        return json_encode($users_reported);
    }



    public function usersReportesPast(Request $request)
    {
        $request->validate([
            'query_string' => 'string|min:0|max:1000' // THE is not such a big username name
        ]);

        $this->authorize('view', Auth::user()->isAdmin); // WORKING

        $users_reported = [];
        $query_string = $request->route('query_string');

        if ($query_string === '*') $query_string = '';

        $users_reported_post = Report::where('id_post', '<>', NULL)
            ->whereIn('user_report.decision', ['Accepted', 'Rejected'])
            ->join('post', 'post.id', '=', 'user_report.id_post')
            ->join('user', 'user.id', '=', 'post.id_poster')
            ->where('username', 'ILIKE', '%' . $query_string . '%')
            ->select('user.id', 'user.username', 'user.photo', 'user.ban_date', 'user_report.decision', 'user_report.decision_date');


        $users_reported_comments = Report::where('id_comment', '<>', NULL)
            ->whereIn('user_report.decision', ['Accepted', 'Rejected'])
            ->join('comment', 'comment.id', '=', 'user_report.id_post')
            ->join('user', 'user.id', '=', 'comment.id_commenter')
            ->where('username', 'ILIKE', '%' . $query_string . '%')
            ->select('user.id', 'user.username', 'user.photo', 'user.ban_date', 'user_report.decision', 'user_report.decision_date');

        $users_reported = $users_reported_post->union($users_reported_comments);

        $users_reported = DB::table(DB::raw("({$users_reported->toSql()}) as sub"))
            ->mergeBindings($users_reported->getQuery())
            ->groupBy('id', 'username', 'photo', 'decision', 'decision_date', 'ban_date')
            ->orderBy('decision_date', 'desc')
            ->limit(10)
            ->get();

        return json_encode($users_reported);
    }
}
