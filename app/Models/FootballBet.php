<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballBet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function body()
    {
        return $this->belongsTo(FootballBody::class, 'body_id');
    }

    public function maung()
    {
        return $this->belongsTo(FootballMaungGroup::class, 'maung_group_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
