<?php

namespace App\Models;

use App\Traits\BalloneBettingAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FootballBody extends Model
{
    use HasFactory, BalloneBettingAttributes;

    protected $guarded = [];

    // Status
    // 0 - Pending , 1 - Win , 2 - Lose , 3 - Draw
    // Refund 0 - false , 1 - true

    protected $appends = [ 'betting_fees' ];

    public function match()
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    public function fees()
    {
        return $this->belongsTo(FootballBodyFee::class, 'fee_id');
    }

    public function bet()
    {
        return $this->hasOne(FootballBet::class, 'body_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

}
