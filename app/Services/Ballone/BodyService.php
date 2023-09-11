<?php

namespace App\Services\Ballone;

use App\Services\RecordService;
use App\Services\UserLogService;

class BodyService
{
    public static function calculate($bodies)
    {
        foreach ($bodies as $body) {

            $result = $body->fees->result;

            $type = $body->type;
            $percent =  $result->$type;
            $betAmount = $body->bet->amount;

            $status = 2;
            $win_amount = ( $betAmount * $percent / 100 );

            if( $percent == 0 ){
                $status = 3;
            }

            if( $percent > 0 ){
                $status = 1;
                $win_amount = $win_amount -  ($win_amount * $body->agent->body_percentage );
            }

            $net_amount = $betAmount + $win_amount;

            if( $net_amount > $betAmount ){
                (new RecordService())->add($body->user, $net_amount, "Body");
            }

            (new UserLogService())->add($body->user, $net_amount, 'Body Win');

            $body->user->increment('amount', $net_amount);
            $body->bet->update(['status' => $status , 'net_amount' => $net_amount ]);
        }

    }
}
