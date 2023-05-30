<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\League;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\FootballMaungFee;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FootballMaungFeeResult;

class MaungFeesController extends Controller
{
    public function index(Request $request)
    {
        $leagues = League::all();
        // $clubs = Club::all();

        $data = FootballMaungFee::where('created_at', '>=', now()->subDays(7))
                                    ->with('match')
                                    ->latest()->get();

        $query = collect($data)->where('match.calculate', 0)->where('match.type', 1);

        return view('backend.admin.ballone.match.maung', compact('leagues', 'query'));
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

        $body = ($request->home_body) ?? $request->away_body;
        $up_team = ($request->home_body) ? 1 : 2;

        $check_null = FootballMaungFee::where('match_id', $match->id)
                                        ->whereNull('body')
                                        ->whereNull('goals')
                                        ->first();

        if( $check_null ){

            $check_null->update([
                'body' => $body,
                'goals' => $request->goals,
                'up_team' => $up_team,
                'by' => Auth::id()
            ]);

        }else{

            $check = FootballMaungFee::where('match_id', $match->id)->count();

            if ($check) {
                FootballMaungFee::where('match_id', $match->id)->update(['status' => 0]);
            }

            $fees = FootballMaungFee::create([
                        'match_id' => $match->id,
                        'body'     => $body,
                        'goals'    => $request->goals,
                        'up_team'  => $up_team,
                        'by'       => Auth::id()
                    ]);

            FootballMaungFeeResult::create([ 'fee_id' => $fees->id ]);

        }

        return response()->json(['success'=>'Match saved successfully.']);
    }

    public function edit($id)
    {
        $match = FootballMatch::with('maungFees', 'home', 'away')->find($id);
        return response()->json($match);
    }
}
