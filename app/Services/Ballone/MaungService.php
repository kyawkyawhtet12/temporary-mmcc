<?php

namespace App\Services\Ballone;

use App\Models\UserLog;
use App\Models\WinRecord;
use App\Models\FootballMaung;
use Illuminate\Support\Facades\DB;

class MaungService
{
    protected $za = [];

    public function __construct()
    {
        $this->za = DB::table("football_maung_zas")->pluck('percent', 'teams');
    }

    public function execute($match_id)
    {
        $maungs = FootballMaung::query()
            ->with(['fees.result', 'bet'])
            ->where('match_id', $match_id)
            ->where('status', 0)
            ->get();

        return DB::transaction(function () use ($maungs) {

            $maung_group_ids = $maungs->pluck('maung_group_id')->unique();

            foreach ($maungs as $maung) {

                $group = $maung->bet->loadCount('teams')->load('bet'); // maung group

                $betting = $group->bet; // football bet

                if ($betting->status == 0) {

                    $result  =  $maung->fees->result;

                    $type    =  $maung->type;

                    $percent =  $result->$type;

                    $betAmount = $betting->net_amount == 0 ? $betting->amount : $betting->net_amount;

                    $status = 1;

                    $betting->net_amount = $betAmount + ($betAmount * ($percent / 100));

                    if ($percent == 0) {

                        $status = 3;

                        $betting->net_amount = $betAmount;
                    }

                    if ($percent == '-100') {

                        $status = 2;

                        $betting->status = 2;

                        $betting->net_amount = 0;

                        $betting->betting_record()->update([
                            'result' => 'No Win',
                            'win_amount' => 0
                        ]);
                    }

                    $maung->update(['status' => $status]);

                    $betting->save();
                }
            }

            return (new MaungWinService())->calculate($maung_group_ids);

        });
    }

    public function calculation($betting, $group)
    {
        $data = FootballMaung::where('maung_group_id', $group->id)
            ->where('status', 0)
            ->count();

        // ပွဲ မကျန်တော့ရင် အလျော်အစားလုပ်

        if ($data == 0 && $betting->status == 0) {

            DB::transaction(function () use ($betting, $group) {

                $charge_percent = ($this->za[$group->teams_count] ?? 0) / 100;

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
            });
        }
    }
}
