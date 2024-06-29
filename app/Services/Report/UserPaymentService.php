<?php

namespace App\Services\Report;

use Illuminate\Support\Facades\DB;

class UserPaymentService
{
    protected $filter;

    public function executeRecord($filter)
    {
        $this->filter = $filter;

        $query = DB::table("user_payment_reports")

        ->when($this->filter['agent_id'] ?? NULL, function ($q){
            return $q->whereIn('agent_id', $this->filter['agent_id']);
        })

        ->when($this->filter['start_date'] ?? NULL, function ($q) {
            return $q->whereDate('created_at', '>=', $this->filter['start_date']);
        })

        ->when($this->filter['end_date'] ?? NULL, function ($q) {
            return $q->whereDate('created_at', '<=', $this->filter['end_date']);
        })

        ->select(

            DB::raw("sum(deposit) as deposit"),
            DB::raw("count(case when deposit != 0 then 1 end) as deposit_count"),

            DB::raw("sum(withdraw) as withdraw"),
            DB::raw("count(case when withdraw != 0 then 1 end) as withdraw_count"),

            DB::raw("sum(deposit) - sum(withdraw) as net_amount"),

            DB::raw("DATE(created_at) as date")
        )

        ->groupBy('date')

        ->orderByDesc('date')

        ->get();

        return $query;

    }


}
