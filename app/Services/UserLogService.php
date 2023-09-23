<?php

namespace App\Services;

use App\Models\UserLog;
class UserLogService
{
    public function add($user, $amount, $operation)
    {
        $end_balance = ($operation == 'Cashout') ? $user->amount - $amount
                                                 : $user->amount + $amount ;

        UserLog::create([
            'agent_id' => $user->agent->id,
            'user_id' => $user->id,
            'operation' => $operation,
            'amount' => $amount,
            'start_balance' => $user->amount,
            'end_balance' => $end_balance
        ]);
    }
}
