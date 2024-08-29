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

    // public function executeRecords($fee_id)
    // {
    //     return FootballBet::query()
    //                     ->whereRelation('body', 'fee_id', $fee_id)
    //                     ->with('user:id,user_id')
    //                     ->with('agent:id,name')
    //                     ->get();
    // }

    public function executeRecords($fee_id)
    {
        return DB::table("football_bets")
        ->join("football_bodies", "football_bodies.id" , "=" , "football_bets.body_id")
        ->where("football_bodies.fee_id", $fee_id)
        ->select(
            'football_bets.id',
            DB::raw('(SELECT user_id FROM users WHERE users.id = football_bets.user_id) as user_id'),
            DB::raw('(SELECT name FROM agents WHERE agents.id = football_bets.agent_id) as agent_name'),
            'football_bets.created_at as betting_time',
            'football_bets.amount as betting_amount',
            'football_bets.status as status',
            'football_bets.net_amount as win_amount',
            'football_bets.body_id as body_id',
            'football_bodies.type as type',
        )
        ->orderByDesc('football_bets.created_at')
        ->get();
    }
}
