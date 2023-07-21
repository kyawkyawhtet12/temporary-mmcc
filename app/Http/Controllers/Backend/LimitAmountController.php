<?php

namespace App\Http\Controllers\Backend;

use App\Models\Agent;
use App\Models\LimitAmount;
use Illuminate\Http\Request;
use App\Models\TwoLimitAmount;
use App\Models\ThreeLimitAmount;
use App\Http\Controllers\Controller;

class LimitAmountController extends Controller
{
    // 2d
    public function limit_2d()
    {
        $agents = Agent::all();
        return view('backend.admin.limit_amounts.2d', compact('agents'));
    }

    public function limit_2d_post(Request $request)
    {
        $agent = Agent::findOrFail($request->agent_id);

        TwoLimitAmount::updateOrCreate(
            ['agent_id' => $agent->id ],
            ['min_amount' => $request->min , 'max_amount' => $request->max ]
        );

        return back()->with('success', '* Successfully Done');
    }

    // 3d
    public function limit_3d()
    {
        $agents = Agent::all();
        return view('backend.admin.limit_amounts.3d', compact('agents'));
    }

    public function limit_3d_post(Request $request)
    {
        $agent = Agent::findOrFail($request->agent_id);

        ThreeLimitAmount::updateOrCreate(
            ['agent_id' => $agent->id ],
            ['min_amount' => $request->min , 'max_amount' => $request->max ]
        );

        return back()->with('success', '* Successfully Done');
    }
}
