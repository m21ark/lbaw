<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function show(String $username, Request $request)
    {
        try {
            validator($request->route()->parameters(), [
                'username' => 'required|exists:user,username',
            ])->validate();
        } catch (Exception $e) {
            return redirect()->route('admin'); // This is necessary as the redirect might go to the previous request and not to the previous page
        }

        $user = User::where('username', $username)->first();
        if ($user === null) // POLICY
            return abort('404');

        // POLICY
        $this->authorize('viewAny', Report::class);

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

        $reportsPost_decided = Report::select('user_report.*')
            ->where('id_post', '<>', NULL)
            ->where('user_report.decision', '<>', 'Pendent')
            ->join('post', 'post.id', '=', 'user_report.id_post')
            ->join('user', 'user.id', '=', 'post.id_poster')
            ->where('username', $username);

        $reportsComments_decided = Report::select('user_report.*')
            ->where('id_comment', '<>', NULL)
            ->where('user_report.decision', '<>', 'Pendent')
            ->join('comment', 'comment.id', '=', 'user_report.id_comment')
            ->join('user', 'user.id', '=', 'comment.id_commenter')
            ->where('username', $username);


        $decided_reports = $reportsPost_decided->union($reportsComments_decided)->get();

        return view('pages.report_item', [
            'user' => $user,
            'statistics' => $statistics,
            'reports' => $reports,
            'decided_reports' => $decided_reports
        ]);
    }

    public function create(Request $request)
    {
        

        $this->authorize('create', Report::class); // POLICY

        $report = new Report();
        $report->report_date = date('Y-m-d H:i:s');
        $report->description = strip_tags($request->description);
        $report->decision = 'Pendent';

        if ($request->id_comment > 0) {
            $request->validate([
                'id_comment' => 'integer|exists:comment,id',
            ]);
            $report->id_comment = $request->id_comment;
        } else {
            $request->validate([
                'id_post' => 'integer|exists:post,id'
            ]);
            $report->id_post = $request->id_post;
        }

        $report->id_reporter = Auth::user()->id;
        $report->id_admin = null;

        $report->save();

        return $report;
    }

    public function rejectAll(Int $userID, Request $request)
    {
        $request->validate([
            'userID' => 'integer|exists:user,id',
        ]);
        $this->authorize('updateAny', Report::class); //POLICY
        $this->updateAllPendent($userID, 'Rejected');
    }

    public function updateAllPendent($userID, $decision)
    {
        
        $this->authorize('updateAny', Report::class); //POLICY

        $reportsPost = Report::select('user_report.*')
            ->where('id_post', '<>', NULL)
            ->where('user_report.decision', 'Pendent')
            ->join('post', 'post.id', '=', 'user_report.id_post')
            ->join('user', 'user.id', '=', 'post.id_poster')
            ->where('user.id', $userID);

        // TODO : VERIFICAR SE É NECESSARIO COM RICARDO POR CAUSA DOS TRIGGERS
        $reportsComments = Report::select('user_report.*')
            ->where('id_comment', '<>', NULL)
            ->where('user_report.decision', 'Pendent')
            ->join('comment', 'comment.id', '=', 'user_report.id_comment')
            ->join('user', 'user.id', '=', 'comment.id_commenter')
            ->where('user.id', $userID);

        $reports = $reportsPost->union($reportsComments)->get();

        foreach ($reports as $report) {
            $report->decision = $decision;
            $report->decision_date = date('Y-m-d H:i:s');
            $report->id_admin = Auth::user()->id;
            $report->save();
        }
    }

    public function banUser(Int $userID, String $time_option, Request $request)
    {
        $request->validate([
            'userID' => 'integer|exists:user,id',
            'time_option' => 'integer|between:1,8'
        ]);

        $this->authorize('updateAny', Report::class); //POLICY

        $user = User::find($userID);
        switch ($time_option) {
            case '1':
                $user->ban_date = date('Y-m-d H:i:s', strtotime('+7 days'));
                break;
            case '2':
                $user->ban_date = date('Y-m-d H:i:s', strtotime('+15 days'));
                break;
            case '3':
                $user->ban_date = date('Y-m-d H:i:s', strtotime('+1 month'));
                break;
            case '4':
                $user->ban_date = date('Y-m-d H:i:s', strtotime('+3 months'));
                break;
            case '5':
                $user->ban_date = date('Y-m-d H:i:s', strtotime('+6 months'));
                break;
            case '6':
                $user->ban_date = date('Y-m-d H:i:s', strtotime('+1 year'));
                break;
            case '7':
                $user->ban_date = date('Y-m-d H:i:s', strtotime('+100 year'));
                break;
            case '8':
                $user->ban_date = null;
                break;
        }

        $user->save();
        $this->updateAllPendent($userID, 'Accepted');
    }

    public function edit(Request $request)
    {

        validator($request->route()->parameters(), [
            'id' => 'integer|exists:report,id',
            'decision' => 'string|in:Pendent,Accepted,Rejected'
        ])->validate();

        $this->authorize('updateAny', Report::class); //POLICY

        $report = Report::find($request->id);

        $report->id_admin = Auth::user()->id;

        $report->decision = $request->decision;
        $report->decision_date = date('Y-m-d H:i:s');

        // TODO ALTERAR BANDATE NO USER
        $report->save();

        return $report;
    }

    public function delete(int $id, Request $request) // TODO: ACHO QUE ISTO N E USADO EM LADO NENHUM
    {

        $request->validate([
            'id' => 'integer|exists:report,id',
        ]);

        $this->authorize('delete', Report::class); //POLICY

        $report = Report::find($id);
        $report->delete();
        return $report;
    }
}
