<?php

namespace App\Services\Ballone;

use App\Models\UserLog;
use App\Models\WinRecord;
use App\Models\FootballMaung;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MaungServiceTest
{
    protected $za = [];

    public function __construct()
    {
        $this->za = DB::table("football_maung_zas")->pluck('percent', 'teams');
    }

    public function execute()
    {
        FootballMaung::query()
            ->with(['fees.result', 'bet'])
            ->where('status', 0)
            ->lazyById(200, function ($maungs) {

         DB::transaction(function () use ($maungs) {

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

                            $betting
                                ->betting_record()
                                ->update([
                                    'result'     => 'No Win',
                                    'win_amount' => 0
                                ]);
                        }

                        $maung->update(['status' => $status]);

                        $betting->save();
                    }
                }

            });

        });
    }
}
