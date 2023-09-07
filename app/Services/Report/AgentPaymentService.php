<?php

namespace App\Services\Report;

use App\Models\Agent;
use App\Models\AgentPaymentAllReport;

class AgentPaymentService
{
    public function handle()
    {
        AgentPaymentAllReport::whereDate('created_at', today())->firstOrCreate();

        Agent::with('payment_reports')
                ->chunkById(500, function ($agents) {
                    foreach ($agents as $agent) {
                        $agent->payment_reports()->create();
                    }
                });
    }
}
