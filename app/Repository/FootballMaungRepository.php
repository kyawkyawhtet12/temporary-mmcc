<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class FootballMaungRepository
{
    protected $round, $agents, $start_date, $end_date;

    public function __construct($data)
    {
        $this->round = $data['round'] ?? NULL;

        $this->agents = $data['agent_id'] ?? NULL;

        $this->start_date = $data['start_date'] ?? NULL;

        $this->end_date = $data['end_$end_date'] ?? NULL;
    }

    public function exceute()
    {
        $maungs = DB::table('football_maungs')

            ->join('football_matches', 'football_matches.id', '=', 'football_maungs.match_id')

            ->when($this->round, function ($q) {
                return $q->whereIn('football_matches.round', $this->round);
            })

            ->when($this->start_date, function ($q) {
                return $q->whereDate('football_matches.created_at', '>=', $this->start_date);
            })

            ->when($this->end_date, function ($q) {
                return $q->whereDate('football_matches.created_at', '<=', $this->end_date);
            })

            ->select('football_maungs.maung_group_id', 'football_matches.round')

            ->groupBy('round', 'maung_group_id');

        $groups = DB::table('betting_records')

            ->join('football_bets', 'betting_records.id', '=', 'football_bets.betting_record_id')

            ->joinSub($maungs, 'maungs', function (JoinClause $join) {
                $join->on('football_bets.maung_group_id', '=', 'maungs.maung_group_id');
            })

            ->when($this->agents, function ($q) {
                return $q->whereIn('betting_records.agent_id', $this->agents);
            })

            ->orderBy('round', 'desc')

            ->select(
                'round',
                DB::raw('SUM(football_bets.amount) as betting_amount'),
                DB::raw('SUM(betting_records.win_amount) as win_amount')
            )

            ->groupBy('round')

            ->get();

        return $groups;

    }

}
