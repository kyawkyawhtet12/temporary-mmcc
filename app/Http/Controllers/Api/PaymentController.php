<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\PaymentProvider;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentDepositResource;
use App\Http\Resources\PaymentWithdrawalResource;

class PaymentController extends Controller
{
    use ApiResponser;

    public function deposit()
    {
        $providers = PaymentProvider::all();
        $data = PaymentDepositResource::collection($providers);
        return $this->successResponse($data);
    }

    public function withdrawal()
    {
        $providers = PaymentProvider::all();
        $data = PaymentWithdrawalResource::collection($providers);
        return $this->successResponse($data);
    }
}
