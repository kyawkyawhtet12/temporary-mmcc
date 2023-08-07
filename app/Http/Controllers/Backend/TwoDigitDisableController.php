<?php

namespace App\Http\Controllers\Backend;

use App\Models\Agent;
use App\Models\TwoDigit;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\TwoDigitStatus;

class TwoDigitDisableController extends Controller
{
    public function index(Request $request)
    {
        $two_digits = TwoDigit::get();
        return view('backend.admin.2d-close.index', compact('two_digits'));
    }

    public function changeTwoDigitEnable(Request $request)
    {
        TwoDigit::whereIn('id', explode(",", $request->ids))->update([
            'status' => $request->status,
            'amount' => 0,
            'date' => null
        ]);

        return response()->json('success');
    }

    public function changeTwoDigitDisable(Request $request)
    {
        TwoDigit::whereIn('id', explode(",", $request->ids))->update([
            'status' => $request->status,
            'amount' => 0,
            'date' => $request->date
        ]);

        return response()->json('success');
    }

    public function changeTwoDigitSubmit(Request $request)
    {
        TwoDigit::whereIn('id', explode(",", $request->ids))->update([
            'amount' => $request->amount,
            'date' => $request->date
        ]);

        return response()->json('success');
    }
}
