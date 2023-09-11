<?php

namespace App\Http\Controllers\Backend\Ballone;

use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\FootballBodyFee;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\FootballBodyFeeResult;
use Illuminate\Support\Facades\Auth;

class BodyFeesController extends Controller
{
    public function index(Request $request)
    {
        $data = FootballBodyFee::with([ 'match' => function($q){
                                    $q->withCount('bodies', 'maungs');
                                }])
                                ->join('football_matches', 'football_matches.id', '=', 'football_body_fees.match_id')
                                ->join('football_body_fee_results', 'football_body_fee_results.fee_id', '=', 'football_body_fees.id')
                                ->join('admins', 'admins.id', '=', 'football_body_fees.by')
                                ->select('football_body_fees.*','football_body_fee_results.*','admins.name as by_user')
                                ->where('football_body_fees.created_at', '>=', now()->subMonth(6))
                                ->orderBy('football_matches.round', 'desc')
                                ->orderBy('football_matches.home_no','asc')
                                ->orderBy('football_body_fees.created_at', 'desc')
                                ->paginate(15);

        $request->session()->forget('prev_route');

        return view('backend.admin.ballone.match.body', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'match_id' => 'required',
            'home_body' => 'required_without:away_body',
            'away_body' => 'required_without:home_body',
            'goals' => 'required'
        ]);

        $match = FootballMatch::find($request->match_id);

        if (!$match) {
            return response()->json(['error'=>'something is wrong.']);
        }

        FootballBodyFee::where('match_id', $match->id)->update([ 'status' => 0 ]);

        $bodyFees = FootballBodyFee::updateOrCreate(
            [ 'match_id' => $match->id , 'body' => NULL , 'goals' => NULL ],
            [
                'body'     => ($request->home_body) ?? $request->away_body,
                'goals'    => $request->goals,
                'up_team'  => ($request->home_body) ? 1 : 2,
                'status'   => 1,
                'by'       => Auth::id()
            ]
        );

        $bodyFees->result()->firstOrCreate();

        return response()->json([ 'success' => 'Match saved successfully.' ]);
    }

    public function edit($id)
    {
        $match = FootballMatch::with('bodyFees', 'home', 'away')->find($id);
        return response()->json($match);
    }
}
