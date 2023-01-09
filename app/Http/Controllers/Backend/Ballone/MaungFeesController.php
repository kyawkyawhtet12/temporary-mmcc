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

class MaungFeesController extends Controller
{
    public function index(Request $request)
    {
        $leagues = League::all();
        // $clubs = Club::all();

        if ($request->ajax()) {
            $query = FootballMatch::whereNull('score')->where('type', 1)
                                    ->with('bodyFees','maungFees')->latest()->get();
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
                        return $match->maungFees?->goals;
                    })
                    ->addColumn('body', function ($match) {
                        return $match->maungFees?->body;
                    })
                    ->addColumn('by', function ($match) {
                        return $match->maungFees?->user?->name;
                    })
                    ->addColumn('action', function ($match) {
                        if($match->maungFees){
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$match->id.'" data-original-title="Edit" class="editMatch mr-2"><i class="fa fa-edit text-inverse m-r-10"></i></a>';
                        }else{
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
                    ->rawColumns(['score','action'])
                    ->make(true);
        }
        return view('backend.admin.ballone.match.maung', compact('leagues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'match_id' => 'required',
            // 'up' => 'required',
            'down' => 'required',
            'condition' => 'required',
            'goal_up' => 'required',
            'goal_down' => 'required',
            'goal_condition' => 'required',
        ]);

        $match = FootballMatch::find($request->match_id);
        if(!$match) return response()->json(['error'=>'something is wrong.']);        

        $check = FootballMaungFee::where('match_id', $match->id)->count();

        if( $check ){
            FootballMaungFee::where('match_id', $match->id)->update(['status' => 0]);
        }

        FootballMaungFee::create([
            'match_id' => $match->id,
            'body'     => $request->up .' '. $request->condition .' '. $request->down,
            'goals'    => $request->goal_up .' '. $request->goal_condition .' '. $request->goal_down,
            'up_team'  => $request->up_team,
            'by'       => Auth::id()
        ]);
          
        return response()->json(['success'=>'Match saved successfully.']);
         
    }

    public function edit($id)
    {
        $match = FootballMatch::with('maungFees','home','away')->find($id);
        return response()->json($match);
    }

}
