<?php

namespace App\Services;

use App\Models\WinRecord;

class RecordService
{
    // new code use from ballone win

    public function executeAddRecord($bet, $amount, $operation)
    {
        WinRecord::firstOrCreate([
            'user_id'    => $bet->user_id,
            'agent_id'   => $bet->agent_id,
            'type'       => $operation,
            'amount'     => $amount,
            'betting_id' => $bet->id
        ]);
    }

    // old code use from 2d,3d win

    public function add($user, $amount, $operation)
    {
        WinRecord::create([
            'user_id'  => $user->id,
            'agent_id' => $user->agent->id,
            'type'     => $operation,
            'amount'   => $amount
        ]);
    }
}
