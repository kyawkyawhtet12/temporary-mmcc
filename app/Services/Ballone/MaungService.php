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

                // $group = $maung->bet->loadCount('teams')->load('bet'); // maung group

                $betting = $maung->bet->bet; // football bet

                if ($betting->status == 0 && $maung->bet->is_done == 0) {

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

                        $betting->is_done = 1;

                        $maung->bet()->update([
                            'status' => 2,
                            'is_done' => 1
                        ]);
                    }

                    $maung->update(['status' => $status]);

                    $betting->save();
                }
            }

            return (new MaungWinService())->calculate($maung_group_ids);

        });
    }


}
