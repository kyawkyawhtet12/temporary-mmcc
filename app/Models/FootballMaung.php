<?php

namespace App\Models;

use App\Traits\BalloneBettingAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FootballMaung extends Model
{
    use HasFactory, BalloneBettingAttributes;

    protected $guarded = [];

    // Status
    // 0 - Pending , 1 - Win , 2 - Lose , 3 - Draw , 4 - Cancel
    // Refund 0 - false , 1 - true

    public function match()
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    public function fees()
    {
        return $this->belongsTo(FootballMaungFee::class, 'fee_id');
    }

    public function bet()
    {
        return $this->belongsTo(FootballMaungGroup::class, 'maung_group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function getChargePercentAttribute()
    {
        $teams = $this->bet->teams()->count();
        $za = FootballMaungZa::where('teams', $teams)->first();

        return $za ? ($za->percent / 100) : 0;
    }

    public function getResultStatusAttribute()
    {
        $result = ($this->match->calculate_maung) ? $this->match->maung_temp_score : '-' ;

        return ($this->match->type == 0) ? "P-P" : ( $this->refund == 1 ? "Refund" : $result );
    }

}
