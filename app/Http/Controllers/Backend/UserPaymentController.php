<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Cashout;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Report\PaymentReportService;
use App\Services\UserLogService;

class UserPaymentController extends Controller
{
    public function store(Request $request)
    {
        $user = User::with('agent')->findOrFail($request->id);

        if ($request->type == 'deposit') {

            DB::transaction(function () use ($request, $user) {

                $payment = Payment::create([
                    'amount' => $request->amount,
                    'user_id' => $user->id,
                    'agent_id' => $user->agent->id,
                    'by' => Auth::id(),
                    'status' => 'Approved'
                ]);

                $user->increment('amount', $request->amount);

                (new UserLogService())->add($user, $request->amount, 'Recharge');
                (new PaymentReportService())->addRecharge($payment);
            });
        } else {

            if ($request->amount > $user->amount) {
                return response()->json(['error' => '* invalid amount']);
            }

            DB::transaction(function () use ($request, $user) {
                $cashout = Cashout::create([
                    'user_id' => $user->id,
                    'amount' => $request->amount,
                    'remark' => "-",
                    'agent_id' => $user->agent->id,
                    'by' => Auth::id(),
                    'status' => 'Approved'
                ]);

                $user->decrement('amount', $request->amount);

                (new UserLogService())->add($user, $request->amount, 'Cashout');
                (new PaymentReportService())->addCashout($cashout);
            });
        }

        return response()->json(['success' => '* Successfully done']);
    }
}
