<?php

namespace App\Services\Report;

use App\Models\UserPaymentReport;
use App\Models\AgentPaymentReport;
use App\Models\AgentPaymentAllReport;
class PaymentReportService
{
    protected $column = "";

    public function addRecharge($payment)
    {
        $this->column = "deposit";
        $this->handle($payment);
    }

    public function addCashout($payment)
    {
        $this->column = "withdraw";
        $this->handle($payment);
    }

    protected function handle($payment)
    {
        $this->addUserPayment($payment);
        $this->addAgentPayment($payment);
        $this->addAgentAllPayment($payment);
    }

    protected function addAgentAllPayment($payment)
    {
        AgentPaymentAllReport::whereDate('created_at', today())
                            ->firstOrCreate()
                            ->increment($this->column, $payment->amount);
    }

    protected function addUserPayment($payment)
    {
        UserPaymentReport::whereDate('created_at', today())
                        ->updateOrCreate([
                            'user_id' => $payment->user_id,
                            'agent_id' => $payment->agent_id
                        ])
                        ->increment($this->column, $payment->amount);
    }

    protected function addAgentPayment($payment)
    {
        AgentPaymentReport::whereDate('created_at', today())
                        ->updateOrCreate([
                            'agent_id' => $payment->agent_id
                        ])
                        ->increment($this->column, $payment->amount);
    }


}
