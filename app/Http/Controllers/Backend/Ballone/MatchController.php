<?php

namespace App\Http\Controllers\Backend\Ballone;

use Carbon\Carbon;
use App\Models\Club;
use App\Models\League;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\FootballMatchStatus;
use App\Http\Controllers\Controller;
use App\Models\ActionRecord;
use App\Models\FootballBodyLimitGroup;
use App\Services\Ballone\RefundService;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
    public function refundHistory(Request $request)
    {
        if ($request->ajax()) {

            if (!empty($request->from_date)) {
                $query = FootballMatch::whereHas('matchStatus', function($q){
                    $q->where('body_refund', 1)
                    ->orWhere('maung_refund', 1);
                })
                ->whereBetween('date_time', [$request->from_date, $request->to_date])
                // ->where('created_at', '>=', now()->subDays(30))
                ->latest()
                ->get();
            } else {
                $query = FootballMatch::whereHas('matchStatus', function($q){
                    $q->where('body_refund', 1)
                    ->orWhere('maung_refund', 1);
                })
                ->where('created_at', '>=', now()->subDays(30))
                ->latest()
                ->get();
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
                ->addColumn('type', function ($match) {
                    $body = $match->matchStatus?->body_refund;
                    $maung = $match->matchStatus?->maung_refund;

                    if( $body && $maung )
                    {
                        return  "All Refund";
                    }else if($body){
                        return "Body Refund";
                    }else if($maung){
                        return "Maung Refund";
                    }else{
                        return "";
                    }
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

        FootballMatch::updateOrCreate(
            ['id' => $request->match_id],
            [
                'score' => $request->up_team . ' ' . '-' . ' ' . $request->down_team,
            ]
        );

        return response()->json(['success' => 'Match saved successfully.']);
    }

    public function show($id)
    {
        $match = FootballMatch::with('home', 'away', 'allBodyFees.match', 'allMaungFees.match')
                            ->find($id);
        return response()->json($match);
    }

    public function edit($id)
    {
        $match = FootballMatch::without('home','away')->findOrFail($id);

        $leagues = League::pluck('name', 'id');

        $clubs = Club::where('league_id', $match->league_id)->get();

        $groups = FootballBodyLimitGroup::orderBy("max_amount")->get();

        $status = str_contains(url()->previous(), 'maung') ?  1 : 0;

        return view("backend.admin.ballone.match.edit", compact('match', 'leagues', 'clubs', 'status', 'groups'));
    }

    // public function update(Request $request, $id)
    // {
    //     // return $request->all();

    //     $request->validate([
    //         'home_no' => 'nullable',
    //         'away_no' => 'nullable',
    //         'league_id' => 'required',
    //         'date' => 'required',
    //         'time' => 'required',
    //         'home_id' => 'required',
    //         'away_id' => 'required',
    //         'limit_group_id' => 'required'
    //     ]);

    //     $date_time = Carbon::createFromFormat("Y-m-d H:i", $request->date . $request->time);

    //     FootballMatch::findOrFail($id)->update([
    //         'round' => $request->round,
    //         'home_no' => $request->home_no,
    //         'away_no' => $request->away_no,
    //         'date_time' => $date_time,
    //         'league_id' => $request->league_id,
    //         'home_id' => $request->home_id,
    //         'away_id' => $request->away_id,
    //         'other' => ($request->other) ?: 0,
    //         'body_limit' => $request->limit_group_id
    //     ]);

    //     $route = ($request->status) ? '/admin/ballone/maung' : '/admin/ballone/body';

    //     return redirect($route)->with('success', '* match successfully updated.');
    // }
    public function update(Request $request, $id)
    {
        // Validate input
        $validated = $request->validate([
            'home_no' => 'nullable',
            'away_no' => 'nullable',
            'league_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'home_id' => 'required',
            'away_id' => 'required',
            'limit_group_id' => 'required'
        ]);

        // Combine date and time into a Carbon instance
        $date_time = Carbon::createFromFormat("Y-m-d H:i", $request->date . $request->time);

        // Find the match and store original data before updating
        $footballMatch = FootballMatch::findOrFail($id);
        $originalData = $footballMatch->getOriginal(); // Get original values before update

        // Update the football match
        $footballMatch->update([
            'round' => $request->round,
            'home_no' => $request->home_no,
            'away_no' => $request->away_no,
            'date_time' => $date_time,
            'league_id' => $request->league_id,
            'home_id' => $request->home_id,
            'away_id' => $request->away_id,
            'other' => ($request->other) ?: 0,
            'body_limit' => $request->limit_group_id
        ]);

        // Get changed fields only
        $changes = $footballMatch->getChanges();

        // Filter only the changed fields for `before` state
        $beforeChanges = array_intersect_key($originalData, $changes);

        // Log action if there are any changes
        if (!empty($changes)) {
            ActionRecord::create([
                'user_id'    => Auth::id(),
                'action'     => 'update',
                'table_name' => 'football_matches',
                'record_id'  => $footballMatch->id,
                'data'       => json_encode([
                    'before' => $beforeChanges,
                    'after'  => $changes
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        }

        $route = ($request->status) ? '/admin/ballone/maung' : '/admin/ballone/body';

        return redirect($route)->with('success', '* Match successfully updated.');
    }

    public function destroy($id)
    {
        FootballMatch::find($id)->delete();
        return response()->json(['success' => 'Match deleted successfully.']);
    }

    public function refund($type, $id)
    {
        $match = FootballMatch::find($id);

        if (!$match) {
            return response()->json('error match');
        }

        DB::transaction(function () use ($type, $match) {

            if( $type == 'body'){
                (new RefundService())->bodyRefund($match);
                $match->matchStatus()->update([ 'body_refund' => 1 ]);
            }

            if( $type == 'maung'){
                (new RefundService())->maungRefund($match);
                $match->matchStatus()->update([ 'maung_refund' => 1 ]);
            }
        });

        return response()->json(['success' => 'Match Refund successfully.']);
    }

    public function close($type, $id, $status)
    {
        $match = FootballMatch::find($id);

        if (!$match) {
            return response()->json('error');
        }

        FootballMatchStatus::updateOrCreate([
            'match_id' => $match->id
        ],[
            'all_close' => ($status == 'open') ? 0 : 1,
            'admin_id'  => auth()->id()
        ]);

        return response()->json(['success' => 'Match closed successfully.']);
    }

    public function getClubs($league_id)
    {
        $clubs = Club::where('league_id', $league_id)->get();
        return response()->json($clubs);
    }
}
