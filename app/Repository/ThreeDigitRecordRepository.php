<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class ThreeDigitRecordRepository
{
    protected $record, $filter;

    public function __construct(protected $data)
    {
        $this->filter = $data['filter'];
    }

    public function execute()
    {
        $query = $this->filterQuery(DB::table("betting_records"))
            ->whereNull('deleted_at')
            ->joinSub($this->getSubQuery(), 'rounds', function (JoinClause $join) {
                $join->on('betting_records.id', '=', 'rounds.betting_record_id');
            })

            ->select(
                DB::raw('SUM(amount) as betting_amount'),
                DB::raw('COUNT(amount) as betting_count'),

                DB::raw('SUM(win_amount) as win_amount'),
                DB::raw('COUNT(CASE when win_amount != 0 then 1 end) as win_count'),

                DB::raw('SUM(amount) - SUM(win_amount) as net_amount'),
                'round',
                'number',
                'result_date'
            )

            ->groupBy('round', 'number', 'result_date')

            ->orderByDesc('result_date');

        return $query;
    }

    public function getSubQuery()
    {
        return DB::table('three_lucky_draws')
            ->whereNull('deleted_at')
            ->join('three_lucky_numbers', 'three_lucky_draws.round', '=', 'three_lucky_numbers.date_id')
            ->leftJoin('three_digits', 'three_lucky_numbers.three_digit_id', '=', 'three_digits.id')
            ->select(
                'three_lucky_draws.betting_record_id',
                'three_digits.number as number',
                'three_lucky_draws.round',
                DB::raw('DATE(three_lucky_numbers.updated_at) as result_date')
            )
            ->groupBy('round', 'betting_record_id', 'number', 'result_date');
    }

    protected function filterQuery($query)
    {
        return $query->where('type', '3D')

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
