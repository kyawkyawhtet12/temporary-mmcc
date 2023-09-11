<?php

namespace App\Http\Controllers\Backend\Ballone;

use Illuminate\Http\Request;
use App\Models\FootballMatch;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Ballone\ResultService;
use Illuminate\Support\Facades\Session;

class AddResultController extends Controller
{
    // Body Result

    public function body($id)
    {
        $match = FootballMatch::with('league', 'home', 'away', 'allBodyFees', 'allBodyFees.result')->findOrFail($id);

        if( strpos( url()->previous(), 'page') ) {
            Session::put("prev_route", url()->previous());
        }

        return view("backend.admin.ballone.match.body-result", compact("match"));
    }

    public function addBody(Request $request, $id, ResultService $resultService)
    {
        $this->validate($request, [
            'home' => 'required',
            'away' => 'required'
        ]);

        $match = FootballMatch::with('allBodyfees.result')->findOrFail($id);

        DB::transaction(function() use ($resultService, $match, $request)
        {
            $resultService->calculate($match->allBodyfees, $request->only('home','away'));

            $match->update([
                'body_temp_score' => "{$request->home}-{$request->away}"
            ]);

        });

        Session::put('refresh', 'true');

        return back();
    }

    // Maung Result

    public function maung($id)
    {
        $match = FootballMatch::with('league', 'home', 'away', 'allMaungFees', 'allMaungFees.result')->findOrFail($id);

        if( strpos( url()->previous(), 'page') ) {
            Session::put("prev_route", url()->previous());
        }

        return view("backend.admin.ballone.match.maung-result", compact("match"));
    }

    public function addMaung(Request $request, $id, ResultService $resultService)
    {
        $this->validate($request, [
            'home' => 'required',
            'away' => 'required'
        ]);

        $match = FootballMatch::with('allMaungfees.result')->findOrFail($id);

        DB::transaction(function() use ($resultService, $match, $request)
        {
            $resultService->calculate($match->allMaungfees, $request->only('home','away'));

            $match->update([
                'maung_temp_score' => "{$request->home}-{$request->away}"
            ]);

        });

        Session::put('refresh', 'true');

        return back();
    }

}
