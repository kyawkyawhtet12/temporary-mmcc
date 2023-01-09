<?php

namespace App\Http\Controllers\Api;

use App\Models\AgentDeposit;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AgentDepositResource;
use App\Models\PaymentProvider;
use Illuminate\Support\Facades\Auth;

class AgentDepositController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $data = AgentDeposit::where('agent_id', Auth::id())->get();
        return $this->successResponse(AgentDepositResource::collection($data));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|numeric',
            'amount' => 'required|numeric|gt:0',
            'account' => 'required',
            'transaction' => 'required|mimes:jpg,jpeg,png'
        ]);

        $payment = PaymentProvider::find($request->payment_id);

        if (!$payment) {
            return $this->errorResponse("Invalid Payment Provider");
        }

        $path = $request->file('transaction')->store('deposits');

        $data = AgentDeposit::create([
                    'agent_id' => Auth::id(),
                    'payment_provider_id' => $request->payment_id,
                    'amount' => $request->amount,
                    'account' => $request->account,
                    'transaction' => $path
                ]);
        
        return $this->successResponse(new AgentDepositResource($data));
    }
}
