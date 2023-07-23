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
        $select_agent = $request->agent ?: 1;

        $two_digits = TwoDigit::with(['rs_status' => function ($query) use ($select_agent) {
                            $query->where('agent_id', $select_agent);
                        }])->get();

        $agents = Agent::all();

        return view('backend.admin.2d-close.index', compact(
            'two_digits', 'agents' , 'select_agent'
        ));
    }

    public function changeTwoDigitEnable(Request $request)
    {
        $ids = explode(",", $request->ids);

        foreach( $ids as $id ){
            TwoDigitStatus::updateOrCreate(
                ['agent_id' => $request->agent, 'two_digit_id' => $id ],
                ['status' => $request->status, 'amount' => 0 , 'date' => null ]
            );
        }

        return response()->json('success');
    }

    public function changeTwoDigitDisable(Request $request)
    {
        $ids = explode(",", $request->ids);

        foreach( $ids as $id ){
            TwoDigitStatus::updateOrCreate(
                ['agent_id' => $request->agent, 'two_digit_id' => $id ],
                ['status' => $request->status, 'amount' => 0 , 'date' => $request->date ]
            );
        }

        return response()->json('success');
    }

    public function changeTwoDigitSubmit(Request $request)
    {
        $ids = explode(",", $request->ids);

        foreach( $ids as $id ){
            TwoDigitStatus::updateOrCreate(
                ['agent_id' => $request->agent, 'two_digit_id' => $id ],
                ['status' => 0 , 'amount' => $request->amount , 'date' => $request->date ]
            );
        }

        return response()->json('success');
    }
}
