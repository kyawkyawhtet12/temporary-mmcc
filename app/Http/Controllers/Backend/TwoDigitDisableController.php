<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Agent;
use App\Models\TwoDigit;
use App\Models\LotteryTime;
use Illuminate\Http\Request;
use App\Models\TwoDigitClose;
use App\Models\TwoDigitStatus;
use Yajra\DataTables\DataTables;
use App\Models\TwoDigitLimitNumber;
use App\Http\Controllers\Controller;

class TwoDigitDisableController extends Controller
{
    public function index(Request $request)
    {
        $agents = Agent::select('id','name')->get();
        $users = User::select('id','name')->get();
        $times = LotteryTime::select("id", "time")->get();

        $data = TwoDigitClose::with('limit_numbers','agent','user','time','admin')->whereDate("date", today())->get();

        return view('backend.admin.2d-close.index', compact('agents', 'users', 'times', 'data'));
    }

    public function store(Request $request)
    {
        // return $request->all();

        foreach( $request->agent_id as $agent_id ){
            $close = TwoDigitClose::updateOrCreate(
                    [
                        'agent_id' => $agent_id,
                        'date'     => $request->date,
                        'lottery_time_id'  => $request->time_id
                    ],
                    [
                        'admin_id' => auth()->id()
                    ]
                );

            foreach( $request->numbers as $number ){

                TwoDigitLimitNumber::updateOrCreate(
                    [
                        'two_digit_close_id' => $close->id,
                        'number'     => $number,
                    ],
                    [
                        'amount' => $request->amount ?: 0
                    ]
                );
            }
        }

        return back();
    }

}
