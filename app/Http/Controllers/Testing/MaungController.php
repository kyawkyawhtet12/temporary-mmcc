<?php

namespace App\Http\Controllers\Testing;

use App\Models\User;
use App\Models\UserLog;
use App\Models\WinRecord;
use App\Models\FootballBet;
use Illuminate\Http\Request;
use App\Models\FootballMaung;
use App\Http\Controllers\Controller;
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
        $groups = [
            50580,
            50600,
            50619,
            50662,
            50687,
            50702,
            50744
        ];

        $maungs = FootballMaung::whereIn('maung_group_id', $groups)->get();

        $service = (new MaungServiceCheck())->execute($maungs);

        return $service;
    }

    public function fix_check()
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


        $bets = FootballBet::whereIn('maung_group_id', $groups)->with('user')->get();

        return $bets;
    }

    public function fix_update()
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

        $bets = FootballBet::whereIn('maung_group_id', $groups)->get();

        foreach ($bets as $bet) {
            $win_amount = $bet->temp_amount - ($bet->temp_amount * 0.15);

            $bet->update([
                'net_amount' => $win_amount,
                'temp_amount' => $win_amount
            ]);

            $bet->betting_record()->update(['win_amount' => $win_amount]);

            WinRecord::where('betting_id', $bet->maung_group_id)
                ->where('status', 0)
                ->update(['amount' => $win_amount]);
        }
    }

    public function user_log()
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

        // return $request->all();

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
}
