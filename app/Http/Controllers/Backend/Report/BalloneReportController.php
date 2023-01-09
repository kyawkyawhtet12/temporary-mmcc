<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use App\Models\FootballBet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BalloneReportController extends Controller
{
    public function today()
    {
        $today = Carbon::today();

        $bet_amount = FootballBet::whereDate('created_at', $today)->sum('amount');
        $bet_count = FootballBet::whereDate('created_at', $today)->count();
        $body_count = FootballBet::whereDate('created_at', $today)->whereNull('maung_group_id')->count();
        $maung_count = FootballBet::whereDate('created_at', $today)->whereNull('body_id')->count();

        $win = FootballBet::whereDate('created_at', $today)->where('status', 1)->sum('net_amount');
        $lose = FootballBet::whereDate('created_at', $today)->where('status', 2)->sum('amount');
        $refund = FootballBet::whereDate('created_at', $today)->where('status', 4)->sum('amount');
        
        $pending = FootballBet::whereDate('created_at', $today)->where('status', 0)->sum('amount');
        $net = $lose - $win;

        // return $body_count;

        return view('backend.admin.ballone.report.today', compact(
            'bet_amount',
            'bet_count',
            'body_count',
            'maung_count',
            'pending',
            'win',
            'lose',
            'refund',
            'net'
        ));
    }
}
