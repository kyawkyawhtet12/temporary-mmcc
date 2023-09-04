<?php

namespace App\Services\Ballone;

use App\Models\UserLog;
use App\Models\WinRecord;

class BodyService
{
    public static function calculate($bodies )
    {
        foreach ($bodies as $body) {

            $result = $body->fees;

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
                WinRecord::create([
                    'user_id' => $body->user_id,
                    'agent_id' => $body->agent_id,
                    'type' => 'Body',
                    'amount' => $net_amount
                ]);
            }

            UserLog::create([
                'user_id' => $body->user_id,
                'agent_id' => $body->agent_id,
                'operation' => 'Body Win',
                'amount' => $net_amount,
                'start_balance' => $body->user->amount,
                'end_balance' => $body->user->amount + $net_amount
            ]);

            $body->user->increment('amount', $net_amount);
            $body->bet->update(['status' => $status , 'net_amount' => $net_amount ]);
        }

    }
}
