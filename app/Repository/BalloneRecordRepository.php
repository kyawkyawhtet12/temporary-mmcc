<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class BalloneRecordRepository
{
    public function __construct(protected $filter = [])
    {
        //
    }

    public function executeRecord($type)
    {
        return $this->generateQueryCollections(
                        $this->getSubQuery($type)
                    );
    }

    // public function getBodyRecord()
    // {
    //     $rounds = $this->getBodyRoundGroup();

    //     return $this->generateQueryCollections($rounds);
    // }

    // public function getMaungRecord()
    // {
    //     $rounds = $this->getMaungRoundGroup();

    //     return $this->generateQueryCollections($rounds);
    // }

    public function getSubQuery($type)
    {
        return match (strtolower($type)) {
            'body'  => $this->getBodyRoundGroup(),
            'maung' => $this->getMaungRoundGroup()
        };
    }

    public function getBodyRoundGroup()
    {
        $query = DB::table('football_bets')
        ->join('football_bodies', 'football_bodies.id', '=', 'football_bets.body_id')
        ->join('football_matches', 'football_matches.id', '=', 'football_bodies.match_id')
        ->select('football_bets.betting_record_id',  'football_matches.round')
        ->groupBy('round', 'betting_record_id');

        return $query;
    }

    public function getMaungRoundGroup()
    {
        $query = DB::table('football_bets')
        ->join('football_maungs', 'football_maungs.maung_group_id', '=', 'football_bets.maung_group_id')
        ->join('football_matches', 'football_matches.id', '=', 'football_maungs.match_id')
        ->select('football_bets.betting_record_id',  'football_matches.round')
        ->groupBy('round', 'betting_record_id');

        return $query;
    }

    protected function generateQueryCollections($subquery)
    {
        $query = DB::table('betting_records')

                    ->joinSub($subquery, 'rounds', function (JoinClause $join) {
                        $join->on('betting_records.id', '=', 'rounds.betting_record_id');
                    });

       return $this->filterQuery($query)
                    ->select(
                        'round',

                        DB::raw('SUM(amount) as betting_amount'),

                        DB::raw('SUM(win_amount) as win_amount'),

                        DB::raw('SUM(amount) - SUM(win_amount) as net_amount'),
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
                return $q->whereIn('round', $this->filter['round'] );
            })

            ->when($this->filter['start_date'] ?? NULL, function ($q) {
                return $q->whereDate('created_at', '>=', $this->filter['start_date']);
            })

            ->when($this->filter['end_date'] ?? NULL, function ($q) {
                return $q->whereDate('created_at', '<=', $this->filter['end_date']);
            });
    }
}
