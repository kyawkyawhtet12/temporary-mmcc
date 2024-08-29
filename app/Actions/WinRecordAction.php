<?php

namespace App\Actions;

use App\Models\WinRecord;
use App\Models\FootballBet;
use Illuminate\Support\Facades\DB;

class WinRecordAction
{
    protected $round;

    public function __construct()
    {
        $this->round = DB::table("football_matches")->latest('round')->first()?->round;
    }

    public function executeCreate($type, $round)
    {

        if ($type == 'Maung') {

            FootballBet::query()
                ->doesnthave('maung_win')
                ->where('round', $round)
                ->maungWinFilter()
                ->chunkById(200, function ($bettings) {

                    DB::transaction(function () use ($bettings) {

                        foreach ($bettings as $betting) {

                            WinRecord::firstOrCreate([
                                'user_id'    => $betting->user_id,
                                'agent_id'   => $betting->agent_id,
                                'type'       => "Maung",
                                'amount'     => $betting->net_amount,
                                'betting_id' => $betting->maung_group_id,
                                'status'     => 0
                            ]);
                        }
                    });
                });
        }
    }
}
