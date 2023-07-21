<?php

namespace App\Http\Controllers\Backend;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TwoDigitCompensation;
use App\Models\ThreeDigitCompensation;

class LimitCompensationController extends Controller
{
    public function limit_2d()
    {
        $agents = Agent::all();
        return view('backend.admin.compensate.2d', compact('agents'));
    }

    public function updateTwoCompensate(Request $request)
    {
        $agent = Agent::findOrFail($request->agent_id);

        TwoDigitCompensation::updateOrCreate(
            ['agent_id' => $agent->id ],
            ['compensate' => $request->compensate ]
        );

        return back()->with('success', '* Successfully Done');
    }

    //

    public function limit_3d()
    {
        $agents = Agent::all();
        return view('backend.admin.compensate.3d', compact('agents'));
    }

    public function updateThreeCompensate(Request $request)
    {
        $agent = Agent::findOrFail($request->agent_id);

        ThreeDigitCompensation::updateOrCreate(
            ['agent_id' => $agent->id ],
            ['compensate' => $request->compensate , 'vote' => 0]
        );

        return back()->with('success', '* Successfully Done');
    }

    // public function updateVote(Request $request)
    // {
    //     ThreeDigitCompensation::find($request->pk)->update(['vote' => $request->value]);
    //     return response()->json(['success'=>'done']);
    // }
}
