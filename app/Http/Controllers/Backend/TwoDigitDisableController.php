<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Agent;
use App\Models\TwoDigit;
use App\Models\LotteryTime;
use Illuminate\Http\Request;
use App\Models\TwoDigitStatus;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class TwoDigitDisableController extends Controller
{
    public function index(Request $request)
    {
        $two_digits = TwoDigit::get();
        $agents = Agent::select('id','name')->get();
        $users = User::select('id','name')->get();
        $times = LotteryTime::select("id", "time")->get();

        return view('backend.admin.2d-close.index', compact('two_digits', 'agents', 'users', 'times'));
    }

    public function store(Request $request)
    {
        return $request->all();
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
            'status' => 0,
            'amount' => $request->amount,
            'date' => $request->date
        ]);

        return response()->json('success');
    }
}
