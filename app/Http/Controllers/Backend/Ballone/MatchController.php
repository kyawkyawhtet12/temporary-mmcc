<?php

namespace App\Http\Controllers\Backend\Ballone;

use Carbon\Carbon;
use App\Models\Club;
use App\Models\User;
use App\Models\League;
use App\Models\FootballBet;
use Illuminate\Support\Str;
use App\Models\FootballBody;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\FootballMaung;
use Laravel\Ui\Presets\React;
use App\Models\FootballBodyFee;
use App\Models\FootballMaungZa;
use App\Models\FootballMaungFee;
use Yajra\DataTables\DataTables;
use App\Models\FootballMaungGroup;
use App\Models\FootballBodySetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FootballBodyFeeResult;
use App\Models\FootballMaungFeeResult;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $data = FootballMatch::where('created_at', '>=', now()->subDays(7))
                            ->with('bodyFees', 'maungFees')->latest()->paginate(30);
        // return $data;
        return view('backend.admin.ballone.match.index', compact('data'));
    }

    public function matchHistory(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $query = FootballMatch::whereNotNull('score')->where('created_at', '>=', now()->subDays(30))->whereBetween('date_time', [$request->from_date, $request->to_date])->orderBy('created_at', 'desc')->orderby('round', 'asc')->get();
            } else {
                $query = FootballMatch::whereNotNull('score')->where('created_at', '>=', now()->subDays(30))->orderBy('created_at', 'desc')->orderby('round', 'asc')->get();
            }
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('league', function ($match) {
                        return $match->league?->name;
                    })
                    ->addColumn('date_time', function ($match) {
                        return get_date_time_format($match);
                    })
                    ->addColumn('match', function ($match) {
                        return "({$match->home_no}) {$match->home?->name} Vs ({$match->away_no}) {$match->away?->name}";
                    })
                    ->addColumn('goals', function ($match) {
                        return $match->fees?->goals;
                    })
                    ->addColumn('body', function ($match) {
                        return $match->fees?->body;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                if (Str::contains(Str::lower($row['match']), Str::lower($request->get('search')))) {
                                    return true;
                                }
                                return false;
                            });
                        }
                    })
                    ->rawColumns(['score','match'])
                    ->make(true);
        }
        return view('backend.admin.ballone.match.history');
    }

    public function refundHistory(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $query = FootballMatch::where('type', 0)->where('created_at', '>=', now()->subDays(30))->whereBetween('date_time', [$request->from_date, $request->to_date])->get();
            } else {
                $query = FootballMatch::where('type', 0)->where('created_at', '>=', now()->subDays(30))->latest()->get();
            }
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('league', function ($match) {
                        return $match->league?->name;
                    })
                    ->addColumn('date_time', function ($match) {
                        return get_date_time_format($match);
                    })
                    ->addColumn('match', function ($match) {
                        return "({$match->home_no}) {$match->home?->name} Vs ({$match->away_no}) {$match->away?->name}";
                    })
                    ->addColumn('goals', function ($match) {
                        return $match->fees?->goals;
                    })
                    ->addColumn('body', function ($match) {
                        return $match->fees?->body;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                if (Str::contains(Str::lower($row['match']), Str::lower($request->get('search')))) {
                                    return true;
                                }
                                return false;
                            });
                        }
                    })
                    ->rawColumns(['score'])
                    ->make(true);
        }
        return view('backend.admin.ballone.match.refund');
    }

    public function create()
    {
        $leagues = League::all();
        return view("backend.admin.ballone.match.create", compact('leagues'));
    }

    public function store(Request $request)
    {
        // return $request->all();
        
        $request->validate([
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
                            'home_no' => $request->home_no[$key],
                            'away_no' => $request->away_no[$key],
                            'date_time' => $date_time,
                            'league_id' => $request->league_id,
                            'home_id' => $request->home_id[$key],
                            'away_id' => $request->away_id[$key]
                        ]);

                $bodyFees = FootballBodyFee::create(['match_id' => $match->id, 'by'=> Auth::id() ]);
                $maungFees = FootballMaungFee::create(['match_id' => $match->id, 'by'=> Auth::id() ]);

                FootballBodyFeeResult::create([ 'fee_id' => $bodyFees->id ]);
                FootballMaungFeeResult::create([ 'fee_id' => $maungFees->id ]);
            }
        }
       
        return redirect('/admin/ballone/match')->with('success', '* match successfully add.');
    }

    public function updateResult(Request $request)
    {
        $request->validate([
            'match_id' => 'required',
            'up_team' => 'required',
            'down_team' => 'required',
        ]);

        // return response()->json($request->all());

        FootballMatch::updateOrCreate(
            [ 'id' => $request->match_id],
            [
              'score' => $request->up_team .' '. '-' .' '. $request->down_team,
            ]
        );

        return response()->json(['success'=>'Match saved successfully.']);
    }

    
    //
    public function show($id)
    {
        $match = FootballMatch::with('home', 'away', 'allBodyFees', 'allBodyFees.match', 'allMaungFees', 'allMaungFees.match')->find($id);
        return response()->json($match);
    }

    public function edit($id)
    {
        $match = FootballMatch::findOrFail($id);
        $leagues = League::all();
        $clubs = Club::where('league_id', $match->league_id)->get();

        if (count($match->bodies) == 0 && count($match->maungs) == 0 && $match->score == null) {
            return view("backend.admin.ballone.match.edit", compact('match', 'leagues', 'clubs'));
        }

        return redirect('/admin/ballone/match')->with('error', '* something is wrong.');
    }

    public function update(Request $request, $id)
    {
        // return $request->all();
        
        $request->validate([
            'home_no' => 'nullable',
            'away_no' => 'nullable',
            'league_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'home_id' => 'required',
            'away_id' => 'required',
        ]);
        $date_time = Carbon::createFromFormat("Y-m-d H:i", $request->date . $request->time);
        FootballMatch::findOrFail($id)->update([
            'home_no' => $request->home_no,
            'away_no' => $request->away_no,
            'date_time' => $date_time,
            'league_id' => $request->league_id,
            'home_id' => $request->home_id,
            'away_id' => $request->away_id
        ]);
       
        return redirect('/admin/ballone/match')->with('success', '* match successfully updated.');
    }

    public function destroy($id)
    {
        FootballMatch::find($id)->delete();
        return response()->json(['success'=>'Match deleted successfully.']);
    }

    public function refund($id)
    {
        $match = FootballMatch::find($id);

        if (!$match) {
            return response()->json('error');
        }
        
        $body = FootballBody::where('match_id', $match->id)->get();
        $maung = FootballMaung::where('match_id', $match->id)->get();

        foreach ($body as $dt) {
            $dt->update([ 'refund' => 1 ]);
            User::findOrFail($dt->user_id)->increment('amount', (int)$dt->bet->amount);
            $dt->bet->update(['status' => 4 ]);
        }

        foreach ($maung as $dt) {
            $dt->update([ 'status' => 4 ,'refund' => 1 ]);
            FootballMaungGroup::find($dt->maung_group_id)->decrement('count', 1);
            $maungGroup = FootballMaungGroup::find($dt->maung_group_id);
            if ($maungGroup->count == 0) {
                FootballBet::where('maung_group_id', $dt->maung_group_id)->update([ 'status' => 4]);
                User::findOrFail($dt->user_id)->increment('amount', (int)$maungGroup->bet->amount);
            }

            $data = FootballMaung::where('maung_group_id', $dt->maung_group_id)
                                    ->where('status', 0)
                                    ->count();

            if ($data == 0) {
                $maungGroup->bet->update(['status' => 1 ]);
            }
        }

        // return response()->json($data);
        $match->update([ 'type' => 0 ]);
        return response()->json(['success' => 'Match Refund successfully.']);
    }

    public function getClubs($league_id)
    {
        $clubs = Club::where('league_id', $league_id)->get();
        return response()->json($clubs);
    }
}
