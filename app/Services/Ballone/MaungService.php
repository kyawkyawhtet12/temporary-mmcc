<?php

namespace App\Services\Ballone;

use App\Models\FootballMaung;
use App\Services\RecordService;
use App\Services\UserLogService;

class MaungService
{
    public function handle($maungs)
    {
        foreach ($maungs as $maung) {

            $betting = $maung->bet->bet;

            if ($betting->status == 0) {

                $result  =  $maung->fees->result;
                $type    =  $maung->type;
                $percent =  $result->$type;

                $betAmount = $betting->net_amount == 0 ? $betting->amount : $betting->net_amount;

                $status = 1;
                $betting->net_amount = $betAmount + ($betAmount * ($percent / 100));

                if ($percent == 0) {
                    $status = 3;
                    $betting->net_amount = $betAmount;
                }

                if( $percent == '-100'){
                    $status = 2;

                    $betting->status = 2;
                    $betting->net_amount = 0;

                    $betting->betting_record()->update([
                        'result' => 'No Win',
                        'win_amount' => 0
                    ]);
                }

                $maung->update([ 'status' => $status ]);

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

        // ပွဲ မကျန်တော့ရင် အလျော်အစားလုပ်
        if ($data == 0 && $betting->status == 0 ) {

            $win_amount = $betting->net_amount;

            $net_amount = (int) ($win_amount - ( $win_amount * $maung->charge_percent) );

            if ($net_amount > $betting->amount) {

                (new RecordService())->add($maung->user, $net_amount, "Maung");

                $betting->betting_record()->update([
                    'result' => 'Win',
                    'win_amount' => $net_amount
                ]);

            }

            (new UserLogService())->add($maung->user, $net_amount, 'Maung Win');

            $maung->user->increment('amount', $net_amount);

            $betting->update([ 'status' => 1 , 'net_amount' => $net_amount ]);
        }
    }
}
