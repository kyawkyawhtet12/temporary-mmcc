<?php

namespace App\Services\Ballone\Maung;

use App\Models\UserLog;
use App\Models\WinRecord;
use App\Models\FootballBet;
use App\Models\FootballMaungGroup;
use Illuminate\Support\Facades\DB;

class WinRecordService
{
    public function execute($round)
    {
        $bets = FootballBet::where('round', $round)
                        ->with('user','agent')
                        ->where('status', 1)
                        ->where('is_done', 0 )
                        ->chunkById(50, function ($bettings) {
                            DB::transaction(function () use ($bettings) {

                                foreach ($bettings as $betting) {

                                    if ($betting->net_amount > $betting->amount) {

                                        $this->addWinRecord($betting, $betting->net_amount);
                                    }

                                    // payment logs

                                    $this->addUserLog($betting, $betting->net_amount);

                                    $betting->update([
                                        'is_done' => 1
                                    ]);
                                }

                            });
                        });
    }

    public function addWinRecord($bet, $amount)
    {
        WinRecord::firstOrCreate([
            'user_id'    => $bet->user_id,
            'agent_id'   => $bet->agent_id,
            'type'       => "Maung",
            'amount'     => $amount,
            'betting_id' => $bet->maung_group_id
        ]);
    }

    public function addUserLog($bet, $amount)
    {
        $logs = UserLog::firstOrCreate([
            'user_id' => $bet->user_id,
            'agent_id' => $bet->agent_id,
            'operation' => 'Maung Win',
            'remark' => $bet->maung_group_id
        ], [
            'amount' => $amount,
            'start_balance' => $bet->user->amount,
            'end_balance' => $bet->user->amount + $amount
        ]);

        if ($logs->wasRecentlyCreated) {
            $bet->user()->increment('amount', $amount);
        }
    }
}
