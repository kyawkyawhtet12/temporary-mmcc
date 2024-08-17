<?php

namespace App\Services\Ballone;

use App\Models\UserLog;
use App\Models\WinRecord;
use App\Models\FootballMaung;
use Illuminate\Support\Facades\DB;

class MaungServiceCheck
{
    public function execute($maungs)
    {
        DB::transaction(function () use ($maungs) {

            foreach ($maungs as $maung) {

                $group = $maung->bet->loadCount('teams')->load('bet'); // maung group

                $betting = $group->bet; // football bet

                $result  =  $maung->fees->result;

                $type    =  $maung->type;

                $percent =  $result->$type;

                $betAmount = $betting->temp_amount == 0 ? $betting->amount : $betting->temp_amount;

                $status = 1;

                $betting->temp_amount = $betAmount + ($betAmount * ($percent / 100));

                if ($percent == 0) {

                    $status = 3;

                    $betting->temp_amount = $betAmount;
                }

                if ($percent == '-100') {

                    $status = 2;

                    $betting->status = 2;

                    $betting->temp_amount = 0;
                }

                $betting->save();

            }

        });
    }



}
