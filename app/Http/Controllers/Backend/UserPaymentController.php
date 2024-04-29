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

        if( !$request->amount ){
            return response()->json([ 'error' => '* Amount is required.' ]);
        }

        if( $request->type == 'cashout' && $request->amount > $user->amount){
            return response()->json([ 'error' => '* Invalid Amount' ]);
        }

        if ($request->type == 'recharge') {

            if( $request->amount < 0 ){
                $this->paymentFix($request, $user);
                return response()->json([ 'success' => '* Successfully Done.' ]);
            }

            $this->deposit($request, $user);
            return response()->json([ 'success' => '* Successfully Done.' ]);
        }

        if( $request->type == 'cashout'){

            if( $request->amount < 0 ){
                return response()->json([ 'error' => '* Invalid Amount' ]);
            }

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

            $user->increment('amount', $request->amount);

            (new UserLogService())->add($user, $request->amount, 'Recharge');
            (new PaymentReportService())->addRecharge($payment);
        });
    }

    protected function paymentFix($request, $user)
    {
        DB::transaction(function () use ($request, $user) {

            $payment = Payment::create([
                'amount' => $request->amount,
                'user_id' => $user->id,
                'agent_id' => $user->agent->id,
                'by' => Auth::id(),
                'status' => 'Approved'
            ]);


            (new UserLogService())->add($user, $request->amount, 'Fix');
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

            $user->decrement('amount', $request->amount);

            (new UserLogService())->add($user, $request->amount, 'Cashout');
            (new PaymentReportService())->addCashout($cashout);
        });
    }

    // delete Payment

    public function destroy(Request $request)
    {
        // return $request->all();
        $payment = Payment::findOrFail($request->id);
        
        $payment->update([
            'status' => 'Rejected'
        ]);

        $payment->user->decrement('amount', +$payment->amount);

        return back()->with("success", "success");
    }
}
