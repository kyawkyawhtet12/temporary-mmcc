<?php

namespace App\Http\Controllers\Testing;

use App\Models\UserLog;
use App\Models\WinRecord;
use App\Models\FootballBet;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\FootballMaung;
use App\Models\FootballMaungGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Ballone\MaungService;
use App\Services\Ballone\MaungServiceTest;
use App\Services\Ballone\MaungServiceCheck;

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
        $percentage = [100, 70, 60];

        $amount = 500;

        foreach ($percentage as $percent) {
            $amount = $amount + $this->percentageAmount($amount, $percent);
        }
        return $amount;
    }

    public function percentageAmount($amount, $percent)
    {
        return $amount * $percent / 100;
    }

    // check and fix

    public function fix($match_id)
    {
        // $groups = FootballMaungGroup::where('round', '335')
        // ->where('status', 0)
        // ->with(['teams'])
        // ->chunkById(200, function ($query) {
        //     foreach ($query as $q) {
        //         (new MaungServiceTest())->execute($q->teams);
        //     }
        // });
        $groups = (new MaungService())->execute($match_id);

        return $groups;
    }

    public function fix_update()
    {
        $bets = FootballBet::where('round', '335')
            ->whereNotNull('maung_group_id')
            ->where('status', 1)
            ->get()
            ->filter(function ($q) {

                if ($q->net_amount != $q->temp_amount) {
                    $q->temp_amount = intval($q->temp_amount - ($q->temp_amount * 0.15));
                    return $q;
                }
            });

        foreach ($bets as $bet) {
            $bet->update([
                'net_amount' => $bet->temp_amount,
                'temp_amount' => $bet->temp_amount
            ]);

            $bet->betting_record()->update(['win_amount' => $bet->temp_amount]);

            WinRecord::where('betting_id', $bet->maung_group_id)
                ->where('status', 0)
                ->where('type', 'Maung')
                ->update(['amount' => $bet->temp_amount]);
        }

        return $bets;
    }

    public function fix_check()
    {

        $bets = FootballBet::where('round', '335')
            ->whereNotNull('maung_group_id')
            ->where('status', 1)
            ->get();

            // return $bets;

         $bets =   $bets->filter(function ($q) {


                if ($q->net_amount != $q->temp_amount) {

                    $q->temp_amount = intval($q->temp_amount - ($q->temp_amount * 0.15));

                    return $q;
                }
            });


            return $bets;
    }
}
