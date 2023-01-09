<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\AgentWithdraw;
use App\Models\PaymentProvider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AgentWithdrawalResource;

class AgentWithdrawalController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $data = AgentWithdraw::where('agent_id', Auth::id())->get();
        return $this->successResponse(AgentWithdrawalResource::collection($data));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|numeric',
            'amount' => 'required|numeric|gt:0',
            'account' => 'required'
        ]);

        $payment = PaymentProvider::find($request->payment_id);

        $current_amount = Auth::user()->amount;

        if ($request->amount > $current_amount) {
            return $this->errorResponse("Invalid Amount");
        }

        if (!$payment) {
            return $this->errorResponse("Invalid Payment Provider");
        }

        $data = AgentWithdraw::create([
                    'agent_id' => Auth::id(),
                    'payment_provider_id' => $request->payment_id,
                    'amount' => $request->amount,
                    'account' => $request->account
                ]);
        
        return $this->successResponse(new AgentWithdrawalResource($data));
    }
}
