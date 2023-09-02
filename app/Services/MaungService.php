<?php

namespace App\Services;

use App\Models\UserLog;
use App\Models\WinRecord;
use App\Models\FootballMaung;

class MaungService
{


    public function calculate($maungs)
    {
        foreach ($maungs as $maung) {
            // $maungGroup = FootballMaungGroup::with('bet')->find($maung->maung_group_id);
            $betting = $maung->bet->bet;

            if ($betting->status == 0) {
                // calculation

                // $result = FootballMaungFeeResult::where('fee_id', $maung->fee_id)->first();

                $result  =  $maung->fees;
                $type    =  $maung->type;
                $percent =  $result->$type;

                $betAmount = $betting->net_amount == 0 ?
                                    $betting->amount :
                                    $betting->net_amount;

                // $bettingStatus = $betting->status;

                $status = 1;
                $betting->net_amount = $betAmount + ($betAmount * ($percent / 100));

                if ($percent == 0) {
                    $status = 3;
                    // $net_amount = $betAmount;
                    $betting->net_amount = $betAmount;
                }

                if( $percent == '-100'){
                    $status = 2;

                    $net_amount = 0;
                    $bettingStatus = 2;

                    $betting->status = 2;
                    $betting->net_amount = 0;
                }

                $maung->update([ 'status' => $status ]);
                // $betting->update([ 'status' => $bettingStatus , 'net_amount' => $net_amount ]);
                $betting->save();

                $this->calculation($betting, $maung);
            }
        }
    }

    public function calculation($betting, $maung )
    {
        $data = FootballMaung::where('maung_group_id', $maung->maung_group_id)
                            ->where('status', 0)
                            ->count();

        $user = $maung->user;

        // မကျန်တော့ရင် အလျော်အစားလုပ်
        if ($data == 0) {

            $win_amount = $betting->net_amount;

            $net_amount = (int) ($win_amount - ( $win_amount * $maung->charge_percent) );

            if ($net_amount > $betting->amount) {
                WinRecord::create([
                    'user_id' => $maung->user_id,
                    'agent_id' => $maung->agent_id,
                    'type' => 'Maung',
                    'amount' => $net_amount
                ]);
            }

            UserLog::create([
                'user_id' => $maung->user_id,
                'agent_id' => $maung->agent_id,
                'operation' => 'Maung Win',
                'amount' => $net_amount,
                'start_balance' => $user->amount,
                'end_balance' => $user->amount + $net_amount
            ]);

            $user->increment('amount', $net_amount);

            $betting->update(['status' => 1, 'net_amount' => $net_amount]);
        }
    }
}
