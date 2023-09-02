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
use App\Services\MaungService;
use Yajra\DataTables\DataTables;
use App\Models\FootballMaungGroup;
use App\Http\Controllers\Controller;
use App\Models\FootballRefundHistory;

class MatchController extends Controller
{

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
                        return $match->match_format;
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

        $status = str_contains(url()->previous(), 'maung') ?  1 : 0;

        return view("backend.admin.ballone.match.edit", compact('match', 'leagues', 'clubs', 'status'));
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
            'round' => $request->round,
            'home_no' => $request->home_no,
            'away_no' => $request->away_no,
            'date_time' => $date_time,
            'league_id' => $request->league_id,
            'home_id' => $request->home_id,
            'away_id' => $request->away_id,
            'other' => ($request->other) ? : 0
        ]);

        $route = ($request->status) ? '/admin/ballone/maung' : '/admin/ballone/body' ;

        return redirect($route)->with('success', '* match successfully updated.');
    }

    public function destroy($id)
    {
        FootballMatch::find($id)->delete();
        return response()->json(['success'=>'Match deleted successfully.']);
    }

    public function refund($id, MaungService $maungService)
    {
        $match = FootballMatch::find($id);

        if (!$match) {
            return response()->json('error');
        }

        $bodies = FootballBody::with("user","bet")->where('match_id', $match->id)->get();

        foreach ($bodies as $body) {

            $body->update([ 'refund' => 1 ]);

            $body->user->increment('amount', (int)$body->bet->amount);
            $body->bet->update(['status' => 4 ]);

            FootballRefundHistory::create([
                'agent_id' => $body->agent_id,
                'user_id'  => $body->user_id,
                'bet_id'   => $body->bet->id
            ]);
        }

        $maungs = FootballMaung::where('match_id', $match->id)->get();

        foreach ($maungs as $maung) {

            $maung->update([ 'status' => 4 ,'refund' => 1 ]);
            // $maungGroup = FootballMaungGroup::find($maung->maung_group_id)->decrement('count', 1);
            // $maungGroup = FootballMaungGroup::find($maung->maung_group_id);
            $betting = $maung->bet->bet;

            // if ($maungGroup->count == 1) {
            //     FootballBet::where('maung_group_id', $maung->maung_group_id)->update([ 'status' => 4]);
            //     User::findOrFail($maung->user_id)->increment('amount', (int)$maungGroup->bet->amount);
            // }



            $maungService->calculation($betting, $maung);
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
