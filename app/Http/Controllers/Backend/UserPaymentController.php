<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Cashout;
use App\Models\Payment;
use App\Models\UserLog;
use Illuminate\Http\Request;
use App\Services\UserLogService;
use App\Models\UserPaymentReport;
use App\Models\AgentPaymentReport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AgentPaymentAllReport;
use App\Services\Report\PaymentReportService;

class UserPaymentController extends Controller
{
    public function store(Request $request)
    {
        $user = User::with('agent')->findOrFail($request->id);

        if( !$request->amount ){
            return response()->json([ 'error' => '* Amount is required.' ]);
        }

        if( $request->type == 'cashout' && $request->amount > $user->amount){
            return response()->json([ 'error' => '* Invalid Amount' ]);
        }

        if( $request->amount < 0 ){
            return response()->json([ 'error' => '* Invalid Amount' ]);
        }

        if ($request->type == 'recharge') {
            $this->deposit($request, $user);
            return response()->json([ 'success' => '* Successfully Done.' ]);
        }

        if( $request->type == 'cashout'){
            $this->cashout($request, $user);
            return response()->json([ 'success' => '* Successfully Done.' ]);
        }
    }

    protected function deposit($request, $user)
    {
        DB::transaction(function () use ($request, $user) {

            $payment = Payment::create([
                'amount' => $request->amount,
                'user_id' => $user->id,
                'agent_id' => $user->agent->id,
                'by' => Auth::id(),
                'status' => 'Approved'
            ]);


            (new UserLogService())->add($user, $request->amount, 'Recharge');
            (new PaymentReportService())->addRecharge($payment);

            $user->increment('amount', $request->amount);

        });
    }

    protected function cashout($request, $user)
    {
        DB::transaction(function () use ($request, $user) {

            $cashout = Cashout::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'remark' => "-",
                'agent_id' => $user->agent->id,
                'by' => Auth::id(),
                'status' => 'Approved'
            ]);

            (new UserLogService())->add($user, $request->amount, 'Cashout');
            (new PaymentReportService())->addCashout($cashout);

            $user->decrement('amount', $request->amount);
        });
    }

    // delete Payment

    public function destroy(Request $request)
    {
        // return $request->all();
        $payment = Payment::findOrFail($request->id);

        $payment->update([ 'status' => 'Rejected' ]);

        $user = $payment->user;

        UserLog::create([
            'agent_id' => $user->agent->id,
            'user_id' => $user->id,
            'operation' => 'Recharge Cancel',
            'amount' => $payment->amount,
            'start_balance' => $user->amount,
            'end_balance' => $user->amount - $payment->amount
        ]);

        AgentPaymentAllReport::whereDate('created_at', $payment->created_at)
        ->decrement( 'deposit', $payment->amount);

        UserPaymentReport::whereDate('created_at', $payment->created_at)
        ->where('user_id' , $payment->user_id)
        ->decrement( 'deposit' , $payment->amount);

        AgentPaymentReport::whereDate('created_at', $payment->created_at)
        ->where('agent_id' , $payment->agent_id )
        ->decrement('deposit', $payment->amount);

        $user->decrement('amount', +$payment->amount);

        return back()->with("success", "success");
    }
}
