<?php

namespace App\Http\Controllers\Backend\Ballone\Maung;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FootballBet;
use App\Services\Ballone\Maung\WinRecordService;

class WinCheckController extends Controller
{
    protected $round;

    public function __construct(protected WinRecordService $winRecordService)
    {
        $this->round = DB::table("football_matches")->latest('round')->first()?->round;
    }

    public function index()
    {
        // return "ok";

        $this->winRecordService->execute($this->round);

        $wins =  FootballBet::where('round', $this->round)
                            ->whereNotNull('maung_group_id')
                            ->with([
                                'user:id,user_id','agent:id,name'
                            ])
                            ->withCount('maung_teams as total_count')
                            ->where('status', 1)
                            ->where('is_done', 1 )
                            ->latest()
                            ->get();

        return view("backend.admin.ballone.match.maung.win_result", compact("wins"));
    }

}
