<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    /*
    id SERIAL PRIMARY KEY,
    report_date DATE NOT NULL,
    "description" TEXT,
    decision_date DATE,
    decision accept_st,
    id_reporter INTEGER NOT NULL REFERENCES "user"(id),
    id_admin INTEGER REFERENCES administrator(id),
    id_comment INTEGER REFERENCES "comment"(id),
    id_post INTEGER REFERENCES post(id)
    */

    public function show(String $username)
    {
        $user = User::where('username', $username)->first();
        if ($user === null)
            return redirect('404');

        $statistics = [
            'post_num' => Post::where('id_poster', $user->id)->count(),
            'comment_num' => DB::table('comment')->where('id_commenter', $user->id)->count(),
            'like_comment_num' => DB::table('like_comment')->where('id_user', $user->id)->count(),
            'like_post_num' => DB::table('like_post')->where('id_user', $user->id)->count(),
            'group_num' => DB::table('group_join_request')->where('id_user', $user->id)->where('acceptance_status', 'Accepted')->count(),
            'friends_num' => DB::table('friend_request')->where('acceptance_status', 'Accepted')->where('id_user_sender', $user->id)->orWhere('id_user_receiver', $user->id)->count(),
        ];

        $reportsPost = Report::select('user_report.*')
            ->where('id_post', '<>', NULL)
            ->where('user_report.decision', 'Pendent')
            ->join('post', 'post.id', '=', 'user_report.id_post')
            ->join('user', 'user.id', '=', 'post.id_poster')
            ->where('username', $username);

        $reportsComments = Report::select('user_report.*')
            ->where('id_comment', '<>', NULL)
            ->where('user_report.decision', 'Pendent')
            ->join('comment', 'comment.id', '=', 'user_report.id_comment')
            ->join('user', 'user.id', '=', 'comment.id_commenter')
            ->where('username', $username);

        $reports = $reportsPost->union($reportsComments)->get();

        return view('pages.report_item', ['user' => $user, 'statistics' => $statistics, 'reports' => $reports]);
    }

    public function create(Request $request)
    {
        // TODO: POLICY
        $report = new Report();
        $report->report_date = date('Y-m-d H:i:s');
        $report->description = $request->description;

        if ($request->id_comment > 0)
            $report->id_comment = $request->id_comment;
        else
            $report->id_post = $request->id_post;

        $report->id_reporter = Auth::user()->id;
        $report->id_admin = null;

        $report->save();

        return $report;
    }

    public function rejectAll(Int $userID)
    {
        return null;
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("HERE1");

        $reportsPost = Report::select('user_report.*')
            ->where('id_post', '<>', NULL)
            ->where('user_report.decision', 'Pendent')
            ->join('post', 'post.id', '=', 'user_report.id_post')
            ->join('user', 'user.id', '=', 'post.id_poster')
            ->where('user.id', $userID);

        $out->writeln("HERE2");

        $reportsComments = Report::select('user_report.*')
            ->where('id_comment', '<>', NULL)
            ->where('user_report.decision', 'Pendent')
            ->join('comment', 'comment.id', '=', 'user_report.id_comment')
            ->join('user', 'user.id', '=', 'comment.id_commenter')
            ->where('user.id', $userID);

        $out->writeln("HERE3");
        $reports = $reportsPost->union($reportsComments)->get();
        $out->writeln("HERE4");
        foreach ($reports as $report) {
            $report->decision = 'Rejected';
            $report->decision_date = date('Y-m-d H:i:s');
            $report->save();
            $out->writeln("HERE6");
        }
        $out->writeln("HERE5");
        return $reports;
    }

    public function edit(Request $request)
    {
        // TODO: POLICY
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("HERE1");
        $report = Report::find($request->id);
        $out->writeln("HERE1_3");
        $out->writeln(Auth::user()->id === null);
        $report->id_admin = Auth::user()->id;
        $out->writeln("HERE2");
        $report->decision = $request->decision;
        $report->decision_date = date('Y-m-d H:i:s');
        $out->writeln("HERE3");
        // TODO ALTERAR BANDATE NO USER
        $report->save();
        $out->writeln("HERE4");
        return $report;
    }

    public function delete(int $id)
    {
        // TODO: POLICY
        // TODO NEM SEI SE FAZ SENTIDO APAGAR REPORT NEM COMO SERIA?
        $report = Report::find($id);
        $report->delete();
        return $report;
    }
}
