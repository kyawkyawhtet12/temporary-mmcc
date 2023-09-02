<?php

namespace App\Http\Controllers\Backend\Ballone;

use Carbon\Carbon;
use App\Models\League;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\FootballBodyFee;
use App\Models\FootballMaungFee;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FootballBodyFeeResult;
use App\Models\FootballMaungFeeResult;

class MaungFeesController extends Controller
{
    public function index(Request $request)
    {
        $data = FootballMaungFee::with('match','result','user', 'match.bodies','match.maungs')
                                ->join('football_matches', 'football_matches.id', '=', 'football_maung_fees.match_id')
                                ->select('football_maung_fees.*')
                                // ->where('football_matches.calculate', 0)
                                // ->where('football_matches.type', 1)
                                ->where('football_maung_fees.created_at', '>=', now()->subMonth(3))
                                ->orderBy('football_matches.round', 'desc')
                                ->orderBy('football_matches.home_no','asc')
                                ->orderBy('football_maung_fees.created_at', 'desc')
                                ->paginate(15);

        $data = FootballMaungFee::where('created_at', '>=', now()->subMonth(6))
                                ->with([
                                    'match' => function($q){
                                        $q->orderBy('round', 'desc')->orderBy('home_no','asc');
                                    },
                                    'result','user'
                                ])
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);

        $request->session()->forget('page');

        return view('backend.admin.ballone.match.maung', compact('data'));
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

    //

    public function add()
    {
        $leagues = League::all();
        $match = FootballMatch::latest()->first();
        $round = $match ? $match->round : 1;
        return view("backend.admin.ballone.match.maung-create", compact('leagues','round'));
    }

    public function create(Request $request)
    {
        // return $request->all();

        $request->validate([
            'round' => 'required',
            'home_no' => 'required|array',
            'away_no' => 'required|array',
            'league_id' => 'required',
            'date' => 'required|array',
            // 'date.*' => 'required',
            'time' => 'required|array',
            // 'time.*' => 'required',
            'home_id' => 'required|array',
            // 'home_id.*' => 'required',
            'away_id' => 'required|array',
            // 'away_id.*' => 'required',
        ]);

        $times = $request->time;

        foreach ($times as $key => $time) {
            if ($request->date[$key] && $request->time[$key]) {
                $date_time = Carbon::createFromFormat("Y-m-d H:i", $request->date[$key] . $request->time[$key]);

                $match = FootballMatch::create([
                            'round' => $request->round,
                            'home_no' => $request->home_no[$key],
                            'away_no' => $request->away_no[$key],
                            'date_time' => $date_time,
                            'league_id' => $request->league_id,
                            'home_id' => $request->home_id[$key],
                            'away_id' => $request->away_id[$key],
                            'other' => ($request->other && array_key_exists($key, $request->other)) ? $request->other[$key] : 0
                        ]);

                $body = ($request->home_body[$key]) ?? $request->away_body[$key];
                $up_team = ($request->home_body[$key]) ? 1 : 2;

                $bodyFees = FootballBodyFee::create(['match_id' => $match->id, 'by'=> Auth::id() ]);

                $maungFees = FootballMaungFee::create([
                    'match_id' => $match->id,
                    'body' =>  $body,
                    'goals' => $request->goals[$key],
                    'up_team' =>  $up_team,
                    'by'=> Auth::id()
                ]);

                FootballBodyFeeResult::create([ 'fee_id' => $bodyFees->id ]);
                FootballMaungFeeResult::create([ 'fee_id' => $maungFees->id ]);
            }
        }

        return redirect('/admin/ballone/maung')->with('success', '* match successfully add.');
    }
}
