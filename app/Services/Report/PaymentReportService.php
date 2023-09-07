<?php

namespace App\Services\Report;

use App\Models\UserPaymentReport;
use App\Models\AgentPaymentReport;
use App\Models\AgentPaymentAllReport;

class PaymentReportService{

    public function addRecharge($payment)
    {
        $this->addReport('deposit', $payment);
    }

    public function addCashout($payment)
    {
        $this->addReport('withdraw', $payment);
    }

    public function addReport($column, $payment)
    {
        UserPaymentReport::whereDate('created_at', today())
                        ->where('user_id', $payment->user_id)
                        ->increment($column, $payment->amount);

        AgentPaymentReport::whereDate('created_at', today())
                            ->where('agent_id', $payment->agent_id)
                            ->increment($column, $payment->amount);

        AgentPaymentAllReport::whereDate('created_at', today())
                            ->increment($column, $payment->amount);
    }


}
