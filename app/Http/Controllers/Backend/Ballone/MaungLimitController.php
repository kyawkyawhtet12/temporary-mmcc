<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Models\FootballMaungLimit;
use App\Http\Controllers\Controller;
use App\Models\MaungTeamSetting;

class MaungLimitController extends Controller
{
    // Maung Minimum Maximum Amount Setting

    public function index(Request $request)
    {
        $agents = Agent::with('maung_limit')->get();
        return view('backend.admin.ballone.maung.limit',compact('agents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'min' => 'required|numeric|min:0',
            'max' => 'required|numeric|min:0',
        ]);

        $agent = Agent::findOrFail($request->agent_id);

        FootballMaungLimit::updateOrCreate(
            [ 'agent_id' => $agent->id ],
            [ 'min_amount' => $request->min , 'max_amount' => $request->max ]
        );

        return back()->with('success', '* Successfully Done');
    }

    // Maung Minimum Maximum Teams Setting

    public function teams_index(Request $request)
    {
        $data = MaungTeamSetting::firstOrFail();
        return view('backend.admin.ballone.maung.setting',compact('data'));
    }

    public function teams_store(Request $request)
    {
        $request->validate([
            'min_teams' => 'required|numeric|min:0',
            'max_teams' => 'required|numeric|min:0',
        ]);

        MaungTeamSetting::firstOrFail()->update([
            'min_teams' => $request->min_teams,
            'max_teams' => $request->max_teams
        ]);

        return back()->with('success', '* Successfully Done');
    }
}
