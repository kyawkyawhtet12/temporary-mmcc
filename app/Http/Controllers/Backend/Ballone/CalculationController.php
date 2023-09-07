<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\FootballMatch;
use App\Models\FootballMaung;
use App\Services\Ballone\MaungService;
use App\Services\Ballone\BodyService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CalculationController extends Controller
{
    protected $page;

    public function __construct()
    {
        $page_no = Session::get("page");
        $this->page = $page_no? "?page={$page_no}" : "";
    }

    public function body($id, BodyService $bodyService)
    {
        // find match id is exist or not
        $match = FootballMatch::with('bodies')->findOrFail($id);

        // Body Calculation
        $bodyService->calculate($match->bodies);

        // Match Calculate finish update
        $match->update([ 'score' => $match->body_temp_score , 'calculate_body' => 1 ]);

        return redirect("/admin/ballone/body{$this->page}")->with('success', '* calculation successfully done.');
    }

    public function maung($id, MaungService $maungService)
    {
        // find match id is exist or not
        $match = FootballMatch::with('maungs')->findOrFail($id);

        $maungs = FootballMaung::with("bet")->where('match_id', $id)->whereStatus(0)->get();
        $maungService->calculate($maungs);

        // Match Calculate finish update
        $match->update([ 'score' => $match->maung_temp_score , 'calculate_maung' => 1 ]);

        return redirect( "/admin/ballone/maung{$this->page}")->with('success', '* calculation successfully done.');
    }

}
