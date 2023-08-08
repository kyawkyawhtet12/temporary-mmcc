<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BettingRecord extends Model
{
    use HasFactory;

    protected $fillable = [ 'agent_id','user_id','amount','type','count','result','win_amount'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function ballone()
    {
        return $this->hasMany(FootballBet::class, 'betting_record_id');
    }

    public function two_digit()
    {
        return $this->hasMany(TwoLuckyDraw::class, 'betting_record_id');
    }

    public function three_digit()
    {
        return $this->hasMany(ThreeLuckyDraw::class, 'betting_record_id');
    }
}
