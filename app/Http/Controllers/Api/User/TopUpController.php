<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ResponseController;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Requests\TopupRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TopUpController extends ResponseController
{
    public function index()
    {
        $user = Auth::user();
        $data = $user->agent->active_payment_providers ?? [];

        if (count($data) == 0) {
            return response()->json(['error' => 'Payment method not found. Please contact your agent.'], 404);
        }

        return $this->successResponse($data, 'Payment Method Fetched Successfully', 200);
    }

    public function store(TopupRequest $request)
    {
        if ($request->amount > 1000000) {
            return response()->json(['error' => 'Maximum limit amount is 1,000,000 MMK.'], 400);
        }

        if ($request->transation_ss) {
            $img = $request->transation_ss;
            $fileName = uniqid('img') . '.' . $img->extension();
            $path = $img->storeAs('deposits', $fileName, 'public');
            $file_path = request()->getSchemeAndHttpHost() . '/storage/' . $path;
        } else {
            $file_path = null;
        }

        $payment = Payment::create([
            'payment_provider_id' => $request->payment_provider_id,
            'amount' => $request->amount,
            'phone' => $request->phone,
            'transation_ss' => $file_path,
        ]);

        return response()->json(['success' => 'Payment request submitted successfully.', 'payment' => $payment]);
    }
}
