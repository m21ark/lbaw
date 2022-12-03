<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function create(Request $request)
    {
        // TODO: POLICY
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();


        $out->writeln( $request);

        $report = new Report();
        $report->report_date = date('Y-m-d H:i:s');
        $report->description = $request->description;

        $report->id_post = $request->id_post;
        //$report->id_comment = $request->id_comment;
        $report->id_reporter = Auth::user()->id;
        $report->id_admin = null;

        $report->save();
        return $report;
    }

    public function edit(Request $request)
    {
        // TODO: POLICY
        $report = Report::find($request->id);
        $report->id_admin = Auth::user()->id;
        $report->decision = $request->decision;
        $report->decision_date = date('Y-m-d H:i:s');
        $report->save();
        return $report;
    }

    public function delete(int $id)
    {
        // TODO: POLICY
        $report = Report::find($id);
        $report->delete();
        return $report;
    }
}
