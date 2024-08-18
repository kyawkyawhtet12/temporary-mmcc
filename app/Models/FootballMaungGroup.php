<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMaungGroup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teams()
    {
        return $this->hasMany(FootballMaung::class, 'maung_group_id');
    }

    public function bet()
    {
        return $this->hasOne(FootballBet::class, 'maung_group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    ///

    public function pending_maungs()
    {
        return $this->hasMany(FootballMaung::class, 'maung_group_id')->where('status', 0);
    }

    public function no_pending_maungs()
    {
        return $this->hasMany(FootballMaung::class, 'maung_group_id')->where('status', '!=' ,0);
    }
}
