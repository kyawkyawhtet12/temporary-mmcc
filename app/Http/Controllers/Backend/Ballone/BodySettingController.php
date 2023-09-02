<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Models\FootballBodySetting;
use App\Http\Controllers\Controller;

class BodySettingController extends Controller
{
    public function index(Request $request)
    {
        $agents = Agent::with('body_limit')->get();
        return view('backend.admin.ballone.body.setting', compact('agents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'min' => 'required|numeric|min:0',
            'max' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0',
        ]);

        $agent = Agent::findOrFail($request->agent_id);

        FootballBodySetting::updateOrCreate(
            [   'agent_id' => $agent->id ],
            [
                'percentage' => $request->percentage,
                'min_amount' => $request->min ,
                'max_amount' => $request->max
            ]
        );

        return back()->with('success', '* Successfully Done');
    }
}
