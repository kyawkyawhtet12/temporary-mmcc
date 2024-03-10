<?php

namespace App\Http\Controllers\Record;

use App\Models\Agent;
use App\Models\FootballBet;
use App\Models\FootballBody;
use Illuminate\Http\Request;
use App\Models\BettingRecord;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\BettingRecord\DetailResource;
use App\Models\FootballMatch;

class BalloneBodyController extends Controller
{
    public function index(Request $request)
    {
        $agents = Agent::select('id', 'name')->get();

        $current_round = FootballMatch::latest()->first()->round;

        if ($request->ajax()) {

            $query = DB::table("football_bodies")
                ->join('football_matches', 'football_matches.id', '=', 'football_bodies.match_id')
                ->join('football_bets', 'football_bets.body_id', '=', 'football_bodies.id')
                ->selectRaw('SUM(amount) as betting_amount , SUM(net_amount) as win_amount , football_matches.round')
                // ->where('football_bets.status', '!=', 0)
                ->when(request('agent_id'), function ($q) {
                    return $q->whereIn('football_bets.agent_id', request('agent_id'));
                })
                ->when(request('round'), function ($q) {
                    return $q->whereIn('football_matches.round', request('round'));
                })
                ->when(request('start_date'), function ($q) {
                    return $q->whereDate('football_matches.created_at', '>=', request('start_date'));
                })
                ->when(request('end_date'), function ($q) {
                    return $q->whereDate('football_matches.created_at', '<=', request('end_date'));
                })
                ->orderBy("football_matches.round", "desc")
                ->groupBy('round');

            return Datatables::of($query)

                ->addIndexColumn()

                ->addColumn('round', function ($q) {
                    return number_format($q->round);
                })

                ->addColumn('betting_amount', function ($q) {
                    return "<span class='bettingAmount' data-amount='{$q->betting_amount}'>". number_format($q->betting_amount) ."</span>";
                })

                ->addColumn('win_amount', function ($q) {
                    return "<span class='winAmount' data-amount='{$q->win_amount}'>". number_format($q->win_amount) ."</span>";
                })

                ->addColumn('net_amount', function ($q) {
                    return number_format($q->betting_amount - $q->win_amount);
                })

                ->addColumn('status', function ($q) {

                    if ($q->betting_amount > $q->win_amount) {
                        return "Win";
                    }

                    if ($q->betting_amount < $q->win_amount) {
                        return "No Win";
                    }

                    return "...";
                })

                ->rawColumns([ 'betting_amount', 'win_amount' ])

                ->make(true);
        }

        return view("backend.record.body", compact('agents', 'current_round'));
    }

    public function detail($id)
    {
        $data = BettingRecord::findOrFail($id);
        return response()->json(new DetailResource($data));
    }
}
