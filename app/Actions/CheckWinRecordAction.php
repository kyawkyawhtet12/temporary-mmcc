<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CheckWinRecordAction
{
    protected $round;
    protected $bettings;

    public function __construct()
    {
        $this->round = DB::table("football_matches")->latest('round')->first()?->round;

        $this->bettings = DB::table('football_maung_groups')
            ->where('round', $this->round)
            ->where('status', 1)
            ->where('is_done', 1)
            ->pluck('id');
    }

    public function executeCheck()
    {
        $query = DB::table("win_records")
        ->where('status', 0)
        ->whereIntegerInRaw('betting_id', $this->bettings)
        ->selectRaw('COUNT(*) as count , betting_id')
        ->groupBy('betting_id')
        ->get();

        return $query->where('count', '>', 1 )->pluck('betting_id')->toArray();
    }
}
