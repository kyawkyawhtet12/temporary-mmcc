<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class BodyRecordRepository
{
    public function __construct(protected $filter = [])
    {
        //
    }

    public function executeRecord()
    {
        $query = DB::table('football_bets')
                ->join('football_bodies', 'football_bodies.id', '=', 'football_bets.body_id')
                ->join('football_matches', 'football_matches.id', '=', 'football_bodies.match_id');

        return $this->filterQuery($query)

            ->select(
                'round',

                DB::raw('SUM(amount) as betting_amount'),
                DB::raw('COUNT(amount) as betting_count'),

                DB::raw('SUM(net_amount) as win_amount'),
                DB::raw('COUNT(CASE when net_amount != 0 then 1 end) as win_count'),

                DB::raw('SUM(amount) - SUM(net_amount) as net_amount'),
            )

            ->orderByDesc('round')

            ->groupBy('round')

            ->get();
    }

    protected function filterQuery($query)
    {
        return $query->when($this->filter['agent_id'] ?? NULL, function ($q) {
            return $q->whereIn('football_bets.agent_id', $this->filter['agent_id']);
        })

            ->when($this->filter['round'] ?? NULL, function ($q) {
                return $q->whereIn('football_bets.round', $this->filter['round']);
            })

            ->when($this->filter['start_date'] ?? NULL, function ($q) {
                return $q->whereDate('football_bets.created_at', '>=', $this->filter['start_date']);
            })

            ->when($this->filter['end_date'] ?? NULL, function ($q) {
                return $q->whereDate('football_bets.created_at', '<=', $this->filter['end_date']);
            });
    }
}
