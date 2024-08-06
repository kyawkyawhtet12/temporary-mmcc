<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class TwoDigitRecordRepository
{
    protected $record, $filter, $type;

    public function __construct(
        protected $data
    )
    {
        $this->filter = $data['filter'];
    }

    public function getJoinSub()
    {
        return DB::table('two_winners')
                ->select(
                    DB::raw('COUNT(*) as count'),
                    'two_lucky_draw_id',
                    'two_lucky_number_id'
                )
                ->groupBy('two_lucky_number_id', 'two_lucky_draw_id');
    }

    public function execute()
    {
        $query = DB::table('two_lucky_draws')

        ->whereNull('deleted_at')

        ->leftJoinSub( $this->getJoinSub() , 'wins', function (JoinClause $join) {
            $join->on( 'wins.two_lucky_draw_id'  , '=', 'two_lucky_draws.id');
        })

        ->when($this->filter['agent_id'] ?? NULL, function ($q) {
            return $q->whereIn('two_lucky_draws.agent_id', $this->filter['agent_id']);
        })

        ->when($this->filter['start_date'] ?? NULL, function ($q) {
            return $q->whereDate('two_lucky_draws.created_at', '>=', $this->filter['start_date']);
        })

        ->when($this->filter['end_date'] ?? NULL, function ($q) {
            return $q->whereDate('two_lucky_draws.created_at', '<=', $this->filter['end_date']);
        })

        ->select(

            'lottery_time_id as time',

            DB::raw('SUM(amount) as betting_amount'),

            DB::raw('DATE(two_lucky_draws.created_at) as date'),

            DB::raw('SUM(CASE when count != 0 then amount * za else 0 end) as win_amount'),

            DB::raw('MAX(za) as za')
        )

        ->groupBy('date', 'time')

        ->orderByDesc('date')

        ->orderByDesc('time');

        return $query;

    }

}
