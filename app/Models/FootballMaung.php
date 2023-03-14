<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FootballMaung extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Status
    // 0 - Pending , 1 - Win , 2 - Lose ,
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

    public function getBettingTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y g:i A');
    }
}
