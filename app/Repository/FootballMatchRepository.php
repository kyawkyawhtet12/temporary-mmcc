<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;


class FootballMatchRepository
{
    public function getReports($data, $current_round)
    {
        return $this->handle($data, $current_round);
    }

    protected function handle($data, $current_round)
    {
        $round = $data['round'] ?? $this->getRounds($current_round);

        $agents = $data['agent_id'] ?? NULL;

        $start_date = $data['start_date'] ?? NULL;

        $end_date = $data['end_$end_date'] ?? NULL ;

        $query = DB::table("football_matches")

            ->when( !$start_date && !$end_date, function ($q) use ($round) {
                return $q->whereIn('round', $round);
            })

            ->when( $start_date , function ($q) use ($start_date) {
                return $q->whereDate('football_matches.created_at', '>=', $start_date);
            })

            ->when( $end_date , function ($q) use ($end_date) {
                return $q->whereDate('football_matches.created_at', '<=', $end_date);
            })

            ->latest()
            ->select('id', 'round')
            ->get()
            ->groupBy(function ($q) {
                return $q->round;
            });

        return $query->map(function ($q) use ($agents) {

            // maung_group_id
            $groups = DB::table("football_maungs")
                ->whereIn('match_id', $q->pluck('id'))
                ->pluck('maung_group_id')->unique();

            $report['round'] = $q->pluck('round')->unique()->first();

            $report['report'] = DB::table("football_bets")
                ->join('betting_records', 'betting_records.id', '=', 'football_bets.betting_record_id')
                ->selectRaw("SUM(betting_records.amount) as betting_amount , SUM(betting_records.win_amount) as win_amount")
                ->whereIn("maung_group_id", $groups)
                ->when($agents, function ($q) use ($agents) {
                    return $q->whereIn('betting_records.agent_id', $agents);
                })
                ->first();

            return $report;
        });
    }

    protected function getRounds($current_round, $limit = 10)
    {
        for ($x = 0; $x < $limit; $x++) {
            $rounds[] = $current_round - $x;
        }

        return $rounds;
    }
}
