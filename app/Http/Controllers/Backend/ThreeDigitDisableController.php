<?php

namespace App\Http\Controllers\Backend;

use App\Models\Agent;
use App\Models\ThreeDigit;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\ThreeDigitStatus;

class ThreeDigitDisableController extends Controller
{
    public function index(Request $request)
    {
        $data = ThreeDigit::get();
        return view('backend.admin.3d-close.index', compact('data'));
    }

    public function changeThreeDigitEnable(Request $request)
    {
        ThreeDigit::whereIn('id', explode(",", $request->ids))->update([
            'status' => $request->status,
            'amount' => 0,
            'date' => null
        ]);

        return response()->json('success');
    }

    public function changeThreeDigitDisable(Request $request)
    {
        ThreeDigit::whereIn('id', explode(",", $request->ids))->update([
            'status' => $request->status,
            'amount' => 0,
            'date' => $request->date
        ]);

        return response()->json('success');
    }

    public function changeThreeDigitSubmit(Request $request)
    {
        ThreeDigit::whereIn('id', explode(",", $request->ids))->update([
            'amount' => $request->amount,
            'date' => $request->date
        ]);

        return response()->json('success');
    }
}
