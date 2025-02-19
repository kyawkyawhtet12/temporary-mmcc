<?php

namespace App\Models;

use App\Traits\FilterQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BettingRecord extends Model
{
    use HasFactory, FilterQuery , SoftDeletes;

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

    public static function setData($draws)
    {
        BettingRecord::whereIn('id', $draws->pluck("betting_record_id")->unique())
                    ->update([
                        'result'     => 'No Win',
                        'win_amount' => 0
                    ]);
    }

    public function pending_body()
    {
        return $this->hasMany(FootballBet::class, 'betting_record_id')->where('status', 0);
    }

    public function getTwoDigitTimeAttribute()
    {
        return $this->two_digit->first()?->lottery_time_id;
    }

    public function getThreeDigitRoundAttribute()
    {
        return $this->three_digit()->first()?->round;
    }
}
