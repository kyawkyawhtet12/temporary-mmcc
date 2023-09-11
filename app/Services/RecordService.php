<?php

namespace App\Services;

use App\Models\WinRecord;

class RecordService
{
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
