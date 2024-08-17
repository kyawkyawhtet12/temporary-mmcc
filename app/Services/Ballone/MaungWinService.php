<?php

namespace App\Services\Ballone;

use App\Models\UserLog;
use App\Models\WinRecord;
use App\Models\FootballMaung;
use App\Models\FootballMaungGroup;
use Illuminate\Support\Facades\DB;

class MaungWinService
{
    protected $za = [];

    public function __construct()
    {
        $this->za = DB::table("football_maung_zas")->pluck('percent', 'teams');
    }

    public function calculate($match_id)
    {
        $group_ids = FootballMaung::query()
            ->where('match_id', $match_id)
            ->pluck('maung_group_id')
            ->unique()
            ->toArray();

        $groups = FootballMaungGroup::query()
            ->withCount('pending_maungs')
            ->having('pending_maungs_count', 0)
            ->with('bet')
            ->whereHas('bet', function ($q) {
                $q->where('status', 0);
            })
            ->whereIn('id', $group_ids)
            ->get();

      return DB::transaction(function () use ($groups) {

            foreach ($groups as $group) {

                $charge_percent = 15 / 100;

                $betting = $group->bet;

                $win_amount = $betting->net_amount;

                $net_amount = (int) ($win_amount - ($win_amount * $charge_percent));

                if ($betting->betting_record->result == 'No Prize') {

                    $result = 'No Win';

                    if ($net_amount > $betting->amount) {

                        WinRecord::firstOrCreate([
                            'user_id'    => $group->user_id,
                            'agent_id'   => $group->agent_id,
                            'type'       => "Maung",
                            'amount'     => $net_amount,
                            'betting_id' => $group->id
                        ]);

                        $result = 'Win';
                    }

                    $betting->betting_record()->update(
                        [
                            'result' => $result,
                            'win_amount' => $net_amount
                        ]
                    );

                    $betting->update(
                        [
                            'status'     => 1,
                            'net_amount' => $net_amount
                        ]
                    );


                    // check user log already add or not
                    $check = UserLog::where('user_id', $group->user_id)
                        ->where('remark', $group->id)
                        ->where('operation', 'Maung Win')
                        ->doesntExist();

                    if ($check) {

                        UserLog::create(
                            [
                                'agent_id' => $group->agent_id,
                                'user_id' => $group->user_id,
                                'remark' => $group->id,
                                'operation' => 'Maung Win',
                                'amount' => $net_amount,
                                'start_balance' => $group->user->amount,
                                'end_balance' => $group->user->amount + $net_amount
                            ]
                        );

                        $group->user()->increment('amount', $net_amount);
                    }
                }
            }

            return $groups;
        });
    }
}
