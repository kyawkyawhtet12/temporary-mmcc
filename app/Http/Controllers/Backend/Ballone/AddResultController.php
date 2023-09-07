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
    public function body($id)
    {
        $match = FootballMatch::with('league', 'home', 'away', 'allBodyFees', 'allBodyFees.result')->findOrFail($id);

        $arr = explode("=", url()->previous());
        if( array_key_exists(1, $arr)) Session::put("page", $arr[1]);

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

        return back();
    }

    public function maung($id)
    {
        $match = FootballMatch::with('league', 'home', 'away', 'allMaungFees', 'allMaungFees.result')->findOrFail($id);

        $arr = explode("=", url()->previous());
        if( array_key_exists(1, $arr)) Session::put("page", $arr[1]);

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

        return back();
    }

}
