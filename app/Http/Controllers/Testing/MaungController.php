<?php

namespace App\Http\Controllers\Testing;

use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\FootballMaung;
use App\Models\FootballMaungGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UserLog;

class MaungController extends Controller
{

    public function calculate()
    {
        $match_id = 13911;

        return 'test';

        $maungs = FootballMaung::query()
                                ->with(['fees.result', 'bet'])
                                ->where('match_id', $match_id)
                                ->where('status', 0)
                                ->get();

        $maung_group_ids = $maungs->pluck('maung_group_id')->unique();


        // return $maungs;



        $percentage = [ 100 , 70 , 60 ];

        $amount = 500;

        foreach( $percentage as $percent )
        {

            $amount = $amount + $this->percentageAmount($amount, $percent);


        }


        return $amount;

    }

    public function percentageAmount($amount, $percent)
    {
        return $amount * $percent / 100 ;
    }

}
