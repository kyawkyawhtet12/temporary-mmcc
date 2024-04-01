<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class FootballBodyRepository
{
    protected $round , $agents , $start_date , $end_date;

    public function __construct($data, $current_round)
    {
        $this->round = $data['round'] ?? $this->getRounds($current_round);

        $this->agents = $data['agent_id'] ?? NULL;

        $this->start_date = $data['start_date'] ?? NULL;

        $this->end_date = $data['end_$end_date'] ?? NULL;
    }

    public function exceute()
    {
        $bodies = DB::table('football_bodies')

            ->join('football_matches', 'football_matches.id', '=', 'football_bodies.match_id')

            ->when(!$this->start_date && !$this->end_date, function ($q) {
                return $q->whereIn('football_matches.round', $this->round);
            })

            ->when( $this->start_date , function ($q) {
                return $q->whereDate('football_matches.created_at', '>=', $this->start_date);
            })

            ->when( $this->end_date , function ($q) {
                return $q->whereDate('football_matches.created_at', '<=', $this->end_date);
            })

            ->select('football_bodies.id','football_matches.round')

            ->groupBy('round','id');

        $groups = DB::table('betting_records')

                    ->join('football_bets', 'betting_records.id', '=', 'football_bets.betting_record_id')

                    ->joinSub($bodies, 'bodies', function (JoinClause $join) {
                        $join->on('football_bets.body_id', '=', 'bodies.id');
                    })

                    ->when($this->agents, function ($q) {
                        return $q->whereIn('betting_records.agent_id', $this->agents);
                    })

                    ->orderBy('round','desc')

                    ->select(
                        'round',
                        DB::raw('SUM(football_bets.amount) as betting_amount'),
                        DB::raw('SUM(betting_records.win_amount) as win_amount')
                    )

                    ->groupBy('round')

                    ->get();

        return $groups;
    }

    protected function getRounds($current_round, $limit = 10)
    {
        for ($x = 0; $x < $limit; $x++) {
            $rounds[] = $current_round - $x;
        }

        return $rounds;
    }
}
