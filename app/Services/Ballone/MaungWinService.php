<?php

namespace App\Services\Ballone;

use App\Models\UserLog;
use App\Models\WinRecord;
use App\Models\FootballMaungGroup;
use Illuminate\Support\Facades\DB;

class MaungWinService
{
    protected $za = [];

    public function __construct()
    {
        $this->za = DB::table("football_maung_zas")->pluck('percent', 'teams');
    }

    public function calculate($maung_group_ids)
    {
        $groups = FootballMaungGroup::query()
                                    ->with('bet')
                                    // ->whereIn('id', $maung_group_ids)
                                    ->whereIntegerInRaw('betting_id', $maung_group_ids)
                                    ->withCount('pending_maungs')
                                    ->having('pending_maungs_count', 0)
                                    ->where('is_done', 0)
                                    ->where('status', '!=', 2)
                                    ->get();

      return DB::transaction(function () use ($groups) {

            foreach ($groups as $group) {

                $charge_percent = 15 / 100;

                $betting = $group->bet;

                $win_amount = $betting->net_amount;

                $net_amount = (int) ($win_amount - ($win_amount * $charge_percent));

                $group->update([
                    'status' => 1,
                    'is_done' => 1
                ]);

                if ($betting->betting_record->result == 'No Prize') {

                    $betting
                    ->betting_record()
                    ->update([
                            'result'     => $net_amount > $betting->amount ? 'Win' : 'No WIn',
                            'win_amount' => $net_amount
                        ]);

                    $betting->update([
                            'status'     => 1,
                            'net_amount' => $net_amount
                        ]);
                }
            }

            return $groups;

        });
    }

    public function addWinRecord($bet, $amount)
    {
        WinRecord::firstOrCreate([
            'user_id'    => $bet->user_id,
            'agent_id'   => $bet->agent_id,
            'type'       => "Maung",
            'amount'     => $amount,
            'betting_id' => $bet->id
        ]);
    }

    public function addUserLog($bet, $amount)
    {
        $logs = UserLog::firstOrCreate([
            'user_id' => $bet->user_id,
            'agent_id' => $bet->agent_id,
            'operation' => 'Maung Win',
            'remark' => $bet->id
        ],[
            'amount' => $amount,
            'start_balance' => $bet->user->amount,
            'end_balance' => $bet->user->amount + $amount
        ]);

        if( $logs->wasRecentlyCreated){

            $bet->user()->increment('amount', $amount);

        }
    }
}
