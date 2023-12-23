<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Agent;
use App\Models\TwoDigit;
use App\Models\LotteryTime;
use Illuminate\Http\Request;
use App\Models\TwoDigitClose;
use App\Models\TwoDigitLimit;
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

        // $d = TwoDigitLimit::whereDate("date", today())->get()->groupBy(['agent.name','lottery_time_id']);

        $data = TwoDigitLimit::whereDate("date", today())->where('lottery_time_id', 1)->get();
        // $collect = collect(json_decode($data[0]->number))->sortKeys();
        // dd($collect);

        $filtered = [
            'time_id' => 1,
            'date' => today()->format('Y-m-d')
        ];

        return view('backend.admin.2d-close.index', compact('agents', 'users', 'times', 'data','filtered'));
    }

    public function store(Request $request)
    {
        // return $request->all();

        foreach( $request->numbers as $number ){
            $limit[intval($number)] = $request->amount ?: 0;
        }

        foreach( $request->agent_id as $agent_id ){

            foreach( $request->time_id as $time ){

                $close = TwoDigitLimit::firstOrCreate([
                    'agent_id' => $agent_id,
                    'date'     => $request->date,
                    'lottery_time_id'  => $time
                ],[
                    'number' => json_encode($limit, true)
                ]);

                $new = $limit + json_decode($close->number, true);

                $close->update(
                        [
                            'admin_id' => auth()->id(),
                            'number' => json_encode($new, true),
                            'amount' => $request->amount ?: 0
                        ]
                    );
            }

            
        }

        return back();
    }

    public function destroy($id)
    {

        if($id == 'all'){
            TwoDigitLimit::all()->delete();
        }else{

            TwoDigitLimit::find($id)->delete();
        }

        return response()->json('success');
    }

    public function old_store(Request $request)
    {
        return $request->all();

        return json_encode($request->numbers);

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
