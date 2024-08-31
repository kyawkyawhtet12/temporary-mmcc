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

            $maungs = $maungs->load(['bet.bet', 'fees.result']);

            foreach ($maungs as $x => $maung) {

                $type    =  $maung->type;

                $betting = $maung->bet->bet; // football bet

                $percent  =  $maung->fees->result->$type;

                $temp_amount = $x == 0 ? 0 : $betting->temp_amount;

                $betAmount = $temp_amount == 0 ? $betting->amount : $betting->temp_amount;

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
