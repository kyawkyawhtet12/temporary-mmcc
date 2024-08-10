<?php

namespace App\Services\Ballone;

use App\Services\RecordService;
use App\Services\UserLogService;

class BodyService
{
    public static function calculate($bodies, $percentage = 0)
    {
        foreach ($bodies as $body) {

            $betting = $body->bet;

            if ($betting->status == 0) {

                $result = $body->fees->result;
                $type      =  $body->type;
                $win_percent   =  $result->$type;
                $betAmount =  $betting->amount;

                $status = 2;
                $win_amount = ( $betAmount * $win_percent / 100 );

                if( $win_percent == 0 ){
                    $status = 3;
                }

                if( $win_percent > 0 ){
                    $status = 1;
                    $win_amount = $win_amount -  ($win_amount * $percentage / 100 ); // အကောက် ပါဆင့် နှုတ်
                }

                $net_amount = $betAmount + $win_amount;

                if( $net_amount > $betAmount ){
                    (new RecordService())->add($body->user, $net_amount, "Body");
                }

                (new UserLogService())->add($body->user, $net_amount, 'Body Win');

                $record = $betting->betting_record;

                $record->increment('win_amount', $net_amount);

                $record->update([
                    'result' => ($record->win_amount > $record->amount ) ? "Win" : "No Win"
                ]);

                $body->user->increment('amount', $net_amount);

                $betting->update([
                    'status' => $status ,
                    'net_amount' => $net_amount
                ]);
            }
        }
    }
}
