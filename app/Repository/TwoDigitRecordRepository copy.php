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

    public function execute()
    {

        $query = $this->filterQuery(DB::table("betting_records"))

            ->select(
                DB::raw('SUM(amount) as betting_amount'),
                DB::raw('SUM(win_amount) as win_amount'),
                DB::raw('SUM(amount) - SUM(win_amount) as net_amount'),
                DB::raw('DATE(betting_records.created_at) as date')
            )

            ->addSelect(
                DB::raw('(SELECT lottery_time_id FROM two_lucky_draws WHERE two_lucky_draws.betting_record_id = betting_records.id LIMIT 1) as time')
            )

            ->groupBy('date', 'time')

            ->orderByDesc('date')
            ->orderByDesc('time');



        return $query;
    }

    protected function filterQuery($query)
    {
        return $query->where('type', '2D')

            ->when($this->filter['agent_id'] ?? NULL, function ($q) {
                return $q->whereIn('agent_id', $this->filter['agent_id']);
            })

            ->when($this->filter['start_date'] ?? NULL, function ($q) {
                return $q->whereDate('created_at', '>=', $this->filter['start_date']);
            })

            ->when($this->filter['end_date'] ?? NULL, function ($q) {
                return $q->whereDate('created_at', '<=', $this->filter['end_date']);
            });
    }

}
