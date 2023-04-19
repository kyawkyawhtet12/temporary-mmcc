<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Cashout;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\UserPaymentReport;
use App\Models\AgentPaymentReport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AgentPaymentAllReport;

class UserPaymentController extends Controller
{
    public function store(Request $request)
    {
        // return $request->all();

        $user = User::findOrFail($request->id);

        $agent = User::getAgent($user->referral_code);

        if($request->type == 'deposit') {
        
            $payment = Payment::create([
                'payment_provider_id' => null,
                'amount' => $request->amount,
                'phone' => null,
                'transation_no' => null,
                'transation_ss' => null,
                'user_id' => $user->id,
                'agent_id' => $agent,
                'by' => Auth::id(),
                'status' => 'Approved'
            ]);

            $user->increment('amount', $request->amount);
            UserPaymentReport::addReport($payment, 'deposit');
            AgentPaymentReport::addReport($payment, 'deposit', $agent);
            AgentPaymentAllReport::addReport($payment, 'deposit');

        } else {

            $cashout = Cashout::create([
                'user_id' => $user->id,
                'payment_provider_id' => null,
                'amount' => $request->amount,
                'phone' => null,
                'remark' => "-",
                'agent_id' => $agent,
                'by' => Auth::id(),
                'status' => 'Approved'
            ]);

            $user->decrement('amount', $request->amount);
            UserPaymentReport::addReport($cashout, 'withdraw');
            AgentPaymentReport::addReport($cashout, 'withdraw', $agent);
            AgentPaymentAllReport::addReport($cashout, 'withdraw');
            
        }
        
        return response()->json(['success' => '* Successfully done']);
    }
}
