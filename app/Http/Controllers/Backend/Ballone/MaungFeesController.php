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

        if ($request->ajax()) {
            // $query = FootballMatch::whereNull('score')->where('type', 1)
            //                         ->with('maungfees')->latest()->get();

            $data = FootballMaungFee::where('created_at', '>=', now()->subDays(7))
                                    ->with('match')
                                    ->latest()->get();

            $query = collect($data)->where('match.calculate', 0)->where('match.type', 1);

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('league', function ($fees) {
                        return $fees->match->league?->name;
                    })
                    ->addColumn('date_time', function ($fees) {
                        return get_date_time_format($fees->match);
                    })
                    ->addColumn('score', function ($fees) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$fees->match->id.'" data-original-title="Edit" class="addResult"><i class="fa fa-plus-square text-inverse m-r-10"></i></a>';
                        if ($fees->match->score === null) {
                            return $btn;
                        }
                        return $fees->match->score;
                    })
                    
                    ->addColumn('match', function ($fees) {
                        return "({$fees->match->home_no}) " . $fees->match->home?->name . " " .$fees->match->other_status . " Vs " . "({$fees->match->away_no}) " . $fees->match->away?->name . " " . $fees->match->other_status;
                    })

                    ->addColumn('goals', function ($fees) {
                        return $fees->goals;
                    })
                    ->addColumn('body_home', function ($fees) {
                        return $fees->up_team == 1 ? $fees->body : '';
                    })
                    ->addColumn('body_away', function ($fees) {
                        return $fees->up_team == 2 ? $fees->body : '';
                    })
                    ->addColumn('by', function ($fees) {
                        return $fees->match->maungfees?->user?->name;
                    })
                    ->addColumn('action', function ($fees) {
                        if ($fees->match->maungfees) {
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$fees->match->id.'" data-original-title="Edit" class="editMatch mr-2"><i class="fa fa-edit text-inverse m-r-10"></i></a>';
                        } else {
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$fees->match->id.'" data-original-title="Edit" class="editMatch mr-2"><i class="fa fa-plus-square text-inverse m-r-10"></i></a>';
                        }
                                               
                        return $btn;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                if (Str::contains(Str::lower($row['home']), Str::lower($request->get('search')))) {
                                    return true;
                                }
                                if (Str::contains(Str::lower($row['away']), Str::lower($request->get('search')))) {
                                    return true;
                                }
                                if (Str::contains(Str::lower($row['round']), Str::lower($request->get('search')))) {
                                    return true;
                                }
                                return false;
                            });
                        }
                    })
                    ->rawColumns(['score','action','body_home', 'body_away','goals', 'match'])
                    ->make(true);
        }
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

        $check = FootballMaungFee::where('match_id', $match->id)->count();

        if ($check) {
            FootballMaungFee::where('match_id', $match->id)->update(['status' => 0]);
        }

        $body = ($request->home_body) ?? $request->away_body;
        $up_team = ($request->home_body) ? 1 : 2;

        FootballMaungFee::where('match_id', $match->id)
                        ->whereNull('body')->whereNull('goals')->delete();

        $fees = FootballMaungFee::create([
                    'match_id' => $match->id,
                    'body'     => $body,
                    'goals'    => $request->goals,
                    'up_team'  => $up_team,
                    'by'       => Auth::id()
                ]);

        FootballMaungFeeResult::create([
            'fee_id' => $fees->id
        ]);
          
        return response()->json(['success'=>'Match saved successfully.']);
    }

    public function edit($id)
    {
        $match = FootballMatch::with('maungFees', 'home', 'away')->find($id);
        return response()->json($match);
    }
}
