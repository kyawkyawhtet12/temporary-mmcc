<?php

namespace App\Http\Controllers\Record;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Models\BettingRecord;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\BettingRecord\DetailResource;
use App\Repository\FootballMatchRepository;

class BalloneMaungController extends Controller
{

    public function index(Request $request)
    {
        $agents = Agent::select('id', 'name')->get();

        $current_round = DB::table('football_matches')->latest()->first()->round;

        if ($request->ajax()) {

            $query = (new FootballMatchRepository())->getReports($request->all(), $current_round);

            return Datatables::of($query)

                ->addIndexColumn()

                ->addColumn('round', function ($q) {
                    return $q['round'];
                })

                ->addColumn('betting_amount', function ($q) {
                    $amount = $q['report']->betting_amount;
                    return "<span class='bettingAmount' data-amount='{$amount}'>". number_format($amount) ."</span>";
                })

                ->addColumn('win_amount', function ($q) {
                    $amount = $q['report']->win_amount;
                    return "<span class='winAmount' data-amount='{$amount}'>". number_format($amount) ."</span>";
                })

                ->addColumn('net_amount', function ($q) {

                    return number_format($q['report']->betting_amount - $q['report']->win_amount);
                })

                ->addColumn('status', function ($q) {

                    if ($q['report']->betting_amount > $q['report']->win_amount ) {
                        return "Win";
                    }

                    if ($q['report']->betting_amount < $q['report']->win_amount) {
                        return "No Win";
                    }

                    return "...";
                })

                ->rawColumns([ 'betting_amount', 'win_amount' ])

                ->make(true);
        }

        return view("backend.record.maung", compact('agents', 'current_round'));
    }

    public function detail($id)
    {
        $data = BettingRecord::findOrFail($id);
        return response()->json(new DetailResource($data));
    }
}
