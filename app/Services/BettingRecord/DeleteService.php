<?php

namespace App\Services\BettingRecord;

use App\Models\UserLog;
use Illuminate\Support\Facades\DB;
use App\Models\TwoDigitTransaction;
use App\Models\ThreeDigitTransaction;

class DeleteService
{
    protected $date;
    protected $record;

    public function __construct($record)
    {
        $this->record = $record;
    }

    public function execute()
    {

        DB::transaction(function () {

            $this->executeService();

            $this->executeUserLog();

            $this->record->delete();

        });
    }

    public function getOperation()
    {
        return match ($this->record->type) {
            '2D' => '2D Cancel',
            '3D' => '3D Cancel',
        };
    }

    public function executeService()
    {
        return match ($this->record->type) {
            '2D' => $this->execute2D(),
            '3D' =>  $this->execute3D(),
        };
    }

    public function executeUserLog()
    {

        DB::transaction(function () {

            $this->record->load('user');

            $user = $this->record->user;

            $log = [
                'agent_id' => $this->record->agent_id,
                'user_id' => $this->record->user_id,
                'amount' => $this->record->amount,
                'operation' => $this->getOperation()
            ];

            $logarr = array_merge($log, [
                'start_balance' => $user->amount,
                'end_balance' => $user->amount + $this->record->amount
            ]);

            UserLog::create($logarr);

            $this->record->user()->increment('amount', $this->record->amount);
        });
    }

    public function execute2D()
    {

        DB::transaction(function () {

            $record = $this->record->load('two_digit');

            $bettings = $record->two_digit()->pluck('amount', 'two_digit_id');

            foreach ($bettings as $id => $amount) {
                TwoDigitTransaction::query()
                    ->where("agent_id", $record->agent_id)
                    ->where('lottery_time_id', $record->two_digit_time)
                    ->whereDate('created_at', $record->created_at)
                    ->where('two_digit_id', $id)
                    ->decrement('amount', $amount);
            }

            $record->two_digit()->delete();
        });
    }


    public function execute3D()
    {

        DB::transaction(function () {

            $record = $this->record->load('three_digit');

            $bettings = $record->three_digit()->pluck('amount', 'three_digit_id');

            foreach ($bettings as $id => $amount) {
                ThreeDigitTransaction::query()
                    ->where("agent_id", $record->agent_id)
                    ->where('round', $record->three_digit_round)
                    ->where('three_digit_id', $id)
                    ->decrement('amount', $amount);
            }

            $record->three_digit()->delete();
        });
    }
}
