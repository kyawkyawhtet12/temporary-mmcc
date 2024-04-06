<?php

namespace App\Http\Controllers\Record;

use App\Models\Agent;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repository\FootballMaungRepository;

class BalloneMaungController extends Controller
{
    public function index(Request $request)
    {
        $agents = Agent::select('id', 'name')->get();

        $current_round = DB::table('football_matches')->latest()->first()?->round;

        if ($request->ajax()) {

            $query = ( new FootballMaungRepository(
                $request->only('round','agent_id','start_date','end_date'),
                $current_round
            ))->exceute();

            return Datatables::of($query)

                ->addIndexColumn()

                ->addColumn('round', function ($q) {
                    return $q->round;
                })

                ->addColumn('betting_amount', function ($q) {
                    $amount = $q->betting_amount ?? 0;
                    return "<span class='bettingAmount' data-amount='{$amount}'>". number_format($amount) ."</span>";
                })

                ->addColumn('win_amount', function ($q) {
                    $amount = $q->win_amount ?? 0;
                    return "<span class='winAmount' data-amount='{$amount}'>". number_format($amount) ."</span>";
                })

                ->addColumn('net_amount', function ($q) {

                    return number_format($q->betting_amount - $q->win_amount);
                })

                ->addColumn('status', function ($q) {

                    if ($q->betting_amount > $q->win_amount ) {
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

        return view("backend.record.maung", compact('agents', 'current_round'));
    }
}
