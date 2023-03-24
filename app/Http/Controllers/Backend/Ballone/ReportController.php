<?php

namespace App\Http\Controllers\Backend\Ballone;

use Carbon\Carbon;
use App\Models\FootballBet;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function bodyTodayReport()
    {
        $data = FootballBet::whereNotNull('body_id')->whereDate('created_at', today())->paginate(10);
        // return $data;
        return view("backend.admin.ballone.report.body", compact('data'));
    }

    public function maungTodayReport()
    {
        // return today();
        $data = FootballBet::whereNotNull('maung_group_id')->whereDate('created_at', today())->paginate(10);
        return view("backend.admin.ballone.report.maung", compact('data'));
    }

    //

    public function index()
    {
        $data = FootballMatch::where('type', 1)
                                ->where(function ($query) {
                                    $query->where('calculate_body', 0)
                                        ->orWhere('calculate_maung', 0);
                                })
                                ->with('bodyFees', 'maungFees')
                                ->whereDate('date', today())
                                ->orWhereDate('date', Carbon::yesterday())
                                ->orderBy('round', 'asc')->paginate(10);

        return view("backend.admin.ballone.report.list", compact('data'));
    }

    public function maung()
    {
        $data = FootballMatch::where('type', 1)
                                ->where(function ($query) {
                                    $query->where('calculate_body', 0)
                                        ->orWhere('calculate_maung', 0);
                                })
                                ->with('bodyFees', 'maungFees')
                                ->orderBy('round', 'asc')->paginate(10);

        return view("backend.admin.ballone.report.maungList", compact('data'));
    }

    public function detail($id)
    {
        $data = FootballMatch::with('home', 'away', 'league', 'allBodyfees', 'allMaungfees')->find($id);
        // return $data;

        return view("backend.admin.ballone.report.list", compact('data'));
    }
}
