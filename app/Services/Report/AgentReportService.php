<?php

namespace App\Services\Report;

use Illuminate\Support\Facades\DB;

class AgentReportService
{
    protected function getColumns($type)
    {
        return match ($type) {
            '2d'        => ['2D'],
            '3d'        => ['3D'],
            'football'  => ['Body', 'Maung']
        };
    }

    public function executeRecord($filter, $type)
    {
        $query = DB::table("betting_records")

            ->join("agents", "agents.id", "betting_records.agent_id")

            ->when($filter['start_date'] ?? NULL, function ($q) use ($filter) {
                return $q->whereDate('betting_records.created_at', '>=', $filter['start_date']);
            })

            ->when($filter['end_date'] ?? NULL, function ($q) use ($filter) {
                return $q->whereDate('betting_records.created_at', '<=', $filter['end_date']);
            })

            ->selectRaw(
                '
                SUM(betting_records.amount) as amount,
                COUNT(DISTINCT user_id) as count ,
                DATE(betting_records.created_at) as date,
                agents.name as agent
            '
            )

            ->latest('date')

            ->groupBy('date', 'agent');

        return $query->whereIn('type', $this->getColumns($type));
    }
}
