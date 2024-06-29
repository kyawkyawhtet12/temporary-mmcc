<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class LotteryRecordRepository
{
    protected $record, $filter, $type;

    protected $two_digit = [
        'group' => 'date' ,
        'sql_raw' => 'DATE(created_at) as date'
    ];

    protected $three_digit = [
        'group' => 'round' ,
        'sql_raw' => '(SELECT max(ROUND) FROM three_lucky_draws WHERE three_lucky_draws.betting_record_id = betting_records.id) as round'
    ];

    public function __construct(
        protected $data
    )
    {
        $this->filter = $data['filter'];
        $this->type = $data['type'];
        $this->record = ($this->type == '2D') ? $this->two_digit : $this->three_digit;
    }

    protected function filterQuery($query)
    {
        return $query->when($this->filter['agent_id'] ?? NULL, function ($q) {
                return $q->whereIn('agent_id', $this->filter['agent_id']);
            })

            ->when($this->filter['start_date'] ?? NULL, function ($q) {
                return $q->whereDate('created_at', '>=', $this->filter['start_date']);
            })

            ->when($this->filter['end_date'] ?? NULL, function ($q) {
                return $q->whereDate('created_at', '<=', $this->filter['end_date']);
            });
    }

    public function getQueryCollections()
    {
        $query = DB::table("betting_records")->where('type', $this->type);

        $query = $this->filterQuery($query)

            ->select(
                DB::raw('SUM(amount) as betting_amount'),
                DB::raw('SUM(win_amount) as win_amount'),
                DB::raw('SUM(amount) - SUM(win_amount) as net_amount'),
                DB::raw($this->record['sql_raw'])
            )

            ->groupBy($this->record['group'])

            ->orderByDesc($this->record['group'])

            ->get();

        return $query;
    }

}
