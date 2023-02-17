<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\League;
use Illuminate\Support\Str;
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
        $leagues = League::all();
        // $clubs = Club::all();

        if ($request->ajax()) {
            $query = FootballMatch::whereNull('score')->where('type', 1)
                                    ->with('bodyFees')->latest()->get();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('league', function ($match) {
                        return $match->league?->name;
                    })
                    ->addColumn('date_time', function ($match) {
                        return date("F j, Y, g:i A", strtotime($match->date_time));
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
                        // return $match->bodyFees?->goals;
                        $old_fees = '';

                        foreach ($match->oldBodyFees as $old) {
                            $old_fees .= "<div style='height:15px'> {$old->goals} </div>";
                        }

                        return "<div>
                                    <div style='height:15px'> {$match->bodyFees?->goals} </div>
                                    $old_fees
                                </div>";
                    })
                    ->addColumn('body', function ($match) {
                        $home = $match->bodyFees?->up_team == 1 ? $match->bodyFees?->body : '';
                        $away = $match->bodyFees?->up_team == 2 ? $match->bodyFees?->body : '';
                        $old_fees = "";

                        foreach ($match->oldBodyFees as $old) {
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
                        return $match->bodyFees?->user?->name;
                    })
                    ->addColumn('action', function ($match) {
                        if ($match->bodyFees) {
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
                    ->rawColumns(['score','action','body','goals'])
                    ->make(true);
        }
        return view('backend.admin.ballone.match.body', compact('leagues'));
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

        $check = FootballBodyFee::where('match_id', $match->id)->count();

        if ($check) {
            FootballBodyFee::where('match_id', $match->id)->update(['status' => 0]);
        }

        $body = ($request->home_body) ?? $request->away_body;
        $up_team = ($request->home_body) ? 1 : 2;

        $fees = FootballBodyFee::create([
                        'match_id' => $match->id,
                        'body'     => $body,
                        'goals'    => $request->goals,
                        'up_team'  => $up_team,
                        'by'       => Auth::id()
                    ]);
        
        FootballBodyFeeResult::create([
            'fee_id' => $fees->id
        ]);
          
        return response()->json(['success'=>'Match saved successfully.']);
    }

    public function edit($id)
    {
        $match = FootballMatch::with('bodyFees', 'home', 'away')->find($id);
        return response()->json($match);
    }
}
