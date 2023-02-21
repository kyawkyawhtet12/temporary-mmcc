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

        if ($request->ajax()) {
            $query = FootballMatch::whereNull('score')->where('type', 1)
                                    ->with('maungfees')->latest()->get();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('league', function ($match) {
                        return $match->league?->name;
                    })
                    ->addColumn('date_time', function ($match) {
                        return get_date_time_format($match);
                    })
                    ->addColumn('score', function ($match) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$match->id.'" data-original-title="Edit" class="addResult"><i class="fa fa-plus-square text-inverse m-r-10"></i></a>';
                        if ($match->score === null) {
                            return $btn;
                        }
                        return $match->score;
                    })
                    ->addColumn('home', function ($match) {
                        return $match->home?->name;
                    })
                    ->addColumn('away', function ($match) {
                        return $match->away?->name;
                    })
                    ->addColumn('goals', function ($match) {
                        $old_fees = '';

                        foreach ($match->oldMaungfees as $old) {
                            $old_fees .= "<div style='height:15px'> {$old->goals} </div>";
                        }

                        return "<div>
                                    <div style='height:15px'> {$match->maungfees?->goals} </div>
                                    $old_fees
                                </div>";
                    })
                    ->addColumn('body', function ($match) {
                        $home = $match->maungfees?->up_team == 1 ? $match->maungfees?->body : '';
                        $away = $match->maungfees?->up_team == 2 ? $match->maungfees?->body : '';
                        $old_fees = "";

                        foreach ($match->oldMaungfees as $old) {
                            $home_old = $old->up_team == 1 ? $old->body : '';
                            $away_old = $old->up_team == 2 ? $old->body : '';
                            $old_fees .= "<div class='d-flex text-center'> 
                                            <div style='width:50px'> $home_old </div> 
                                            <div style='height:15px;border-right:1px solid #333;margin:0 5px;'></div> 
                                            <div style='width:50px'> $away_old </div> 
                                        </div>";
                        }

                        return "<div class='d-flex text-center'> 
                                    <div style='width:50px'> $home </div> 
                                    <div style='height:15px;border-right:1px solid #333;margin:0 5px;'></div> 
                                    <div style='width:50px'> $away </div> 
                                </div>

                                $old_fees
                                
                            ";
                    })
                    ->addColumn('by', function ($match) {
                        return $match->maungfees?->user?->name;
                    })
                    ->addColumn('action', function ($match) {
                        if ($match->maungfees) {
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$match->id.'" data-original-title="Edit" class="editMatch mr-2"><i class="fa fa-edit text-inverse m-r-10"></i></a>';
                        } else {
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$match->id.'" data-original-title="Edit" class="editMatch mr-2"><i class="fa fa-plus-square text-inverse m-r-10"></i></a>';
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
                    ->rawColumns(['score','action', 'body', 'goals'])
                    ->make(true);
        }
        return view('backend.admin.ballone.match.maung', compact('leagues'));
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
