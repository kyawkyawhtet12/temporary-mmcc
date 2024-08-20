<?php

namespace App\Http\Controllers\Testing;

use App\Models\User;
use App\Models\UserLog;
use App\Models\WinRecord;
use App\Models\FootballBet;
use Illuminate\Http\Request;
use App\Models\FootballMaung;
use App\Http\Controllers\Controller;
use App\Models\FootballMaungGroup;
use App\Services\Ballone\MaungServiceCheck;

class MaungController extends Controller
{

    public function temp_amount_reset()
    {
        $groups = [
            50580,
            50600,
            50619,
            50662,
            50687,
            50702,
            50744
        ];

        // $bets = FootballBet::whereIn('maung_group_id', $groups)->update([ 'temp_amount' => 0 ]);
        $bets = FootballBet::query()->update(['temp_amount' => 0]);

        return $bets;
    }

    public function fix()
    {
        $groups = [ 52117 ];

        $maungs = FootballMaung::whereIn('maung_group_id', $groups)->get();

        $service = (new MaungServiceCheck())->execute($maungs);

        return $service;
    }

    public function fix_check()
    {
        $groups = [ 52117 ];

        $bets = FootballBet::whereIn('maung_group_id', $groups)->with('user')->get();

        return $bets;
    }

    public function fix_update()
    {

        $groups = [ 52117 ];

        $bets = FootballBet::whereIn('maung_group_id', $groups)->get();

        foreach ($bets as $bet) {
            $win_amount = $bet->temp_amount - ($bet->temp_amount * 0.15);

            $bet->update([
                'net_amount' => $win_amount,
                'temp_amount' => $win_amount
            ]);

            $bet->betting_record()->update([ 'win_amount' => $win_amount ]);

            if ($win_amount > $bet->amount) {

                WinRecord::firstOrCreate([
                    'user_id'    => $bet->user_id,
                    'agent_id'   => $bet->agent_id,
                    'type'       => "Maung",
                    'amount'     => $win_amount,
                    'betting_id' => $bet->maung_group_id
                ]);
            }

            $logs = UserLog::firstOrCreate([
                'user_id' => $bet->user_id,
                'agent_id' => $bet->agent_id,
                'operation' => 'Maung Win',
                'remark' => $bet->maung_group_id
            ],[
                'amount' => $win_amount,
                'start_balance' => $bet->user->amount,
                'end_balance' => $bet->user->amount + $win_amount
            ]);

            if( $logs->wasRecentlyCreated){
                $bet->user()->increment('amount', $win_amount);
            }
        }
    }

    //

    public function user_log()
    {
        $groups = [ 52117 ];

        $logs = UserLog::whereIn('remark', $groups)->where('operation', 'Maung Win')->get();

        return $logs;
    }

    public function user_log_fix($id)
    {
        $user = User::find($id);

        return view("backend.admin.users.user_log", compact("user"));
    }

    public function user_log_add(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $end_balance = ($request->type === 'increment')
            ? $user->amount + $request->amount
            : $user->amount - $request->amount;

        UserLog::create([
            'agent_id' => $user->agent->id,
            'user_id' => $user->id,
            'remark' => $request->remark,
            'operation' => $request->operation,
            'amount' => $request->amount,
            'start_balance' => $user->amount,
            'end_balance' => $end_balance
        ]);


        if ($request->type === 'increment') {
            $user->increment("amount", $request->amount);
        }

        if ($request->type === 'decrement') {
            $user->decrement("amount", $request->amount);
        }

        return back()->with('success', 'success');
    }

    //calculate test
    public function calculate_test()
    {
        // $groups = [ 50799, 50800, 50797, 50798 ];

        $match_id = 13903;



        $group_ids = FootballMaung::where('match_id', $match_id)->pluck('maung_group_id')->unique()->toArray();


        // dd($group_ids);

        $groups = FootballMaungGroup::withCount('pending_maungs')
        ->with('bet')
        ->whereHas('bet', function($q){
            $q->where('status', 0);
        })
        ->having('pending_maungs_count', 0)
        ->whereIn('id', $group_ids)
        ->get();



        return $groups;
    }
}
