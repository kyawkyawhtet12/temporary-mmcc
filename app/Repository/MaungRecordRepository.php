<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class MaungRecordRepository
{
    public function __construct(protected $filter = [])
    {
        //
    }

    public function getSubQuery()
    {
        $query = DB::table('football_maungs')
            ->join('football_matches', 'football_matches.id', '=', 'football_maungs.match_id')
            ->select('football_maungs.maung_group_id',  'football_matches.round')
            ->groupBy('round', 'maung_group_id');

        return $query;
    }

    public function executeRecord()
    {
        $query = DB::table('football_bets')

            ->leftJoin('football_maung_groups', 'football_maung_groups.id', '=', 'football_bets.maung_group_id')

            ->joinSub($this->getSubQuery(), 'rounds', function (JoinClause $join) {
                $join->on('football_maung_groups.id', '=', 'rounds.maung_group_id');
            });

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
            return $q->whereIn('agent_id', $this->filter['agent_id']);
        })

            ->when($this->filter['round'] ?? NULL, function ($q) {
                return $q->whereIn('round', $this->filter['round']);
            })

            ->when($this->filter['start_date'] ?? NULL, function ($q) {
                return $q->whereDate('created_at', '>=', $this->filter['start_date']);
            })

            ->when($this->filter['end_date'] ?? NULL, function ($q) {
                return $q->whereDate('created_at', '<=', $this->filter['end_date']);
            });
    }
}
