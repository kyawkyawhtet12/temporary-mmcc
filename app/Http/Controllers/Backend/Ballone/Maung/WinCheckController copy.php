<?php

namespace App\Http\Controllers\Backend\Ballone\Maung;

use App\Models\FootballBet;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Ballone\Maung\WinRecordService;

class WinCheckController extends Controller
{
    protected $round;

    protected $limit = 2;

    public function __construct(protected WinRecordService $winRecordService)
    {
        $this->round = DB::table("football_matches")->latest('round')->first()?->round;
    }

    public function index()
    {
        if(!request()->page){
            $this->winRecordService->execute($this->round);
        }

        $query =  FootballBet::query()
                            ->where('round', $this->round)
                            ->maungWinFilter();

        $newquery = clone $query;

        $data = $query->selectRaw(
            '
                SUM(net_amount > amount) as total_win ,
                SUM(net_amount < amount) as total_nowin ,
                COUNT(*) as total_count ,
                SUM(net_amount) as total_win_amount
            '
        )
        ->first()
        ->toArray();

        Arr::set($data, 'round', $this->round);

        // return $data;

        $wins = $newquery->with('user:id,user_id')
        ->with('agent:id,name')
        ->withCount('maung_teams as total_count')
        ->latest()
        ->paginate($this->limit);


        // return $wins;



        return view("backend.admin.ballone.match.maung.win_result", compact("wins", "data"));
    }

}
