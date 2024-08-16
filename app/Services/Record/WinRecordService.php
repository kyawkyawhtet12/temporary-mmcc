<?php

namespace App\Services\Record;

use App\Models\UserLog;
use App\Models\WinRecord;
use Illuminate\Support\Facades\DB;

class WinRecordService
{
    public function executeDelete($record)
    {

        DB::transaction(function () use ($record) {

            UserLog::create([
                'agent_id' => $record->agent_id,
                'user_id' => $record->user_id,
                'operation' => "{$record->type} Win Fix",
                'amount' => $record->amount,
                'start_balance' => $record->user->amount,
                'end_balance' => $record->user->amount - $record->amount
            ]);

            $record->user()->decrement('amount', $record->amount);

            // $record->delete();

            $record->update([ 'type' => "{$record->type} Win Fix"]);

        });
    }
}
