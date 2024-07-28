<?php

namespace App\Services\Daily;

use App\Models\User;
use App\Models\Agent;
use App\Models\AgentPaymentAllReport;

class PaymentReportService
{
    public function handle()
    {
        $this->addUserPayments();
        $this->addAgentPayments();
        $this->addAgentAllPayments();
    }

    protected function addUserPayments()
    {
        User::has('agent')->with('agent','payment_reports')
                ->chunkById(500, function ($users) {
                    foreach ($users as $user) {
                        $user->payment_reports()->updateOrCreate([
                            'agent_id' => $user->agent?->id
                        ]);
                    }
                });
    }

    protected function addAgentPayments()
    {
        Agent::with('payment_reports')
                ->chunkById(500, function ($agents) {
                    foreach ($agents as $agent) {
                        $agent->payment_reports()->firstOrCreate();
                    }
                });
    }

    protected function addAgentAllPayments()
    {
        AgentPaymentAllReport::whereDate('created_at', today())->firstOrCreate();
    }
}
