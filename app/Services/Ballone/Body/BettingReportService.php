<?php

namespace App\Services\Ballone\Body;

use App\Models\FootballBet;
use App\Models\FootballBody;
use Illuminate\Support\Facades\DB;

class BettingReportService
{
    public function executeReportAmount($fee_id)
    {

        return DB::table("football_bets")
                ->join("football_bodies", "football_bodies.id" , "=" , "football_bets.body_id")
                ->where("football_bodies.fee_id", $fee_id)
                ->select(
                    DB::raw('SUM(football_bets.amount) as total'),
                    'football_bodies.type as type'
                )
                ->groupBy('type')
                ->pluck('total', 'type');
    }

    public function executeRecords($fee_id)
    {
        return FootballBet::query()
                        ->whereRelation('body', 'fee_id', $fee_id)
                        ->with('user:id,user_id')
                        ->with('agent:id,name')
                        ->get();
    }
}
