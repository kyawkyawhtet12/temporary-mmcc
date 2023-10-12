<?php

namespace App\Traits;

use Carbon\Carbon;

trait BalloneBettingAttributes
{
    public function getBettingTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y g:i A');
    }

    public function getBettingTypeAttribute()
    {
        $typeAttributes = [
            'home'  => $this->match->home_team,
            'away'  => $this->match->away_team,
            'over'  => "Goal Over",
            'under' => "Goal Under"
        ];

        return $typeAttributes[$this->type];
    }

    public function getBettingFeesAttribute()
    {
        $upteam = ( $this->fees->up_team == 1) ? $this->match->home_team : $this->match->away_team;

        $typeAttributes = [
            'home'  => "{$upteam} {$this->fees->body}",
            'away'  => "{$upteam} {$this->fees->body}",
            'over'  => $this->fees->goals,
            'under' => $this->fees->goals
        ];

        return $typeAttributes[$this->type];
    }


}
