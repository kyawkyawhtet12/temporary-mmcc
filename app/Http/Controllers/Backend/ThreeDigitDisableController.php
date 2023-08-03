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
        $select_agent = $request->agent ?: 1;

        $data = ThreeDigit::with(['rs_status' => function ($query) use ($select_agent) {
                            $query->where('agent_id', $select_agent);
                        }])->get();

        $agents = Agent::all();

        return view('backend.admin.3d-close.index', compact(
            'data', 'agents' , 'select_agent'
        ));
    }

    public function changeThreeDigitEnable(Request $request)
    {
        $ids = explode(",", $request->ids);

        foreach( $ids as $id ){
            ThreeDigitStatus::updateOrCreate(
                ['agent_id' => $request->agent, 'three_digit_id' => $id ],
                ['status' => $request->status, 'amount' => 0 , 'date' => null ]
            );
        }

        return response()->json('success');
    }

    public function changeThreeDigitDisable(Request $request)
    {
        $ids = explode(",", $request->ids);

        foreach( $ids as $id ){
            ThreeDigitStatus::updateOrCreate(
                ['agent_id' => $request->agent, 'three_digit_id' => $id ],
                ['status' => $request->status, 'amount' => 0  ]
            );
        }

        return response()->json('success');
    }

    public function changeThreeDigitSubmit(Request $request)
    {
        $ids = explode(",", $request->ids);

        foreach( $ids as $id ){
            ThreeDigitStatus::updateOrCreate(
                ['agent_id' => $request->agent, 'three_digit_id' => $id ],
                ['status' => 0 , 'amount' => $request->amount ]
            );
        }

        return response()->json('success');
    }
}
