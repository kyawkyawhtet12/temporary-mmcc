<?php

namespace App\Http\Controllers\Testing;

use App\Models\UserLog;
use App\Models\WinRecord;
use App\Models\FootballBet;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\FootballMaung;
use App\Models\FootballMaungGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Ballone\MaungServiceCheck;

class MaungController extends Controller
{

    public function calculate()
    {
        $match_id = 13911;

        return 'test';
        $maungs = FootballMaung::query()
                                ->with(['fees.result', 'bet'])
                                ->where('match_id', $match_id)
                                ->where('status', 0)
                                ->get();

        $maung_group_ids = $maungs->pluck('maung_group_id')->unique();

        // return $maungs;
        $percentage = [ 100 , 70 , 60 ];

        $amount = 500;

        foreach( $percentage as $percent )
        {
            $amount = $amount + $this->percentageAmount($amount, $percent);
        }
        return $amount;

    }

    public function percentageAmount($amount, $percent)
    {
        return $amount * $percent / 100 ;
    }

    // check and fix

    public function fix($id)
    {
        $groups = [ 52117 ];

        // $maungs = FootballMaung::whereIn('maung_group_id', $groups)->get();
        $maungs = FootballMaung::where('maung_group_id', $id)->get();

        $service = (new MaungServiceCheck())->execute($maungs);

        return $service;
    }

    public function fix_check($id)
    {
        // $groups = [ 52117 ];

        // $bets = FootballBet::whereIn('maung_group_id', $groups)->with('user')->get();
        $bets = FootballBet::where('maung_group_id', $id)->with('user')->get();

        return $bets;
    }

    public function fix_update($id)
    {

        // $groups = [ 52117 ];

        $bets = FootballBet::where('maung_group_id', $id)->get();

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
}
