<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoLuckyDraw extends Model
{
    use HasFactory;

    public $table = 'two_lucky_draws';

    protected $fillable = [
        'user_id',
        'agent_id',
        'two_digit_id',
        'amount',
        'lottery_time_id',
        'za'
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function twodigit()
    {
        return $this->belongsTo(TwoDigit::class, 'two_digit_id');
    }

    public function lottery_time()
    {
        return $this->belongsTo(LotteryTime::class, 'lottery_time_id');
    }

    public function betting_record()
    {
        return $this->belongsTo(BettingRecord::class, 'betting_record_id');
    }

    public function scopeFilterDates($query)
    {
        $date = explode(" - ", request()->input('date_range', ""));
        if (count($date) != 2) {
            $date = [now()->today()->format("Y-m-d"), now()->format("Y-m-d")];
        }
        return $query->whereBetween('created_at', [$date['0'] . " 00:00:00", $date['1'] . " 23:59:59"]);
    }

    public function scopeFilterTime($query)
    {
        if (!empty(request()->get('lottery_time'))) {
            return $query->where('lottery_time', request()->input('lottery_time'));
        }
    }

    public function getWinAmountAttribute()
    {
        return $this->amount * $this->za;
    }
}
