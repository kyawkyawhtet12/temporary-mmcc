<?php

namespace App\Services\Daily;

use App\Models\Cashout;
use App\Models\Payment;
use App\Models\UserLog;
use App\Services\UserLogService;
use Illuminate\Support\Facades\DB;

class PaymentRejectService
{
    public function execute()
    {
        DB::transaction(function () {

            $this->executeDeposit();

            $this->executeWithdrawal();
        });
    }

    public function executeDeposit()
    {
        $query = Payment::pending()->filterNotToday();

        if ($query->exists()) {
            $query->update(['status' => 'Rejected']);
        }
    }

    public function executeWithdrawal()
    {
        $query = Cashout::query()
            ->with('user')
            ->pending()
            ->filterNotToday();

        if ($query->exists()) {

            DB::transaction(function () use ($query) {

                $query->update(['status' => 'Rejected']);

                $cashouts = $query->get();

                foreach ($cashouts as $cashout) {

                    $check = UserLog::where('remark', $cashout->id)
                    ->where('operation', 'Cashout Reject')
                    ->where('user_id', $cashout->user_id)
                    ->doesntExist();

                    if ($check) {

                        UserLog::create([
                            'agent_id' => $cashout->agent_id,
                            'user_id' => $cashout->user_id,
                            'operation' => 'Cashout Reject',
                            'amount' => $cashout->amount,
                            'start_balance' => $cashout->user->amount,
                            'end_balance' => $cashout->user->amount + $cashout->amount,
                            'remark' => $cashout->id
                        ]);

                        $cashout->user()->increment('amount', $cashout->amount);
                    }
                }
            });
        }
    }
}
