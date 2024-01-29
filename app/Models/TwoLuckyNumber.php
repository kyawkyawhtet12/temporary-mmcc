<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoLuckyNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'lottery_time_id',
        'date',
        'two_digit_id',
        'status',
        'type'
    ];

    public function two_digit()
    {
        return $this->belongsTo(TwoDigit::class, 'two_digit_id');
    }

    public function lottery_time()
    {
        return $this->belongsTo(LotteryTime::class, 'lottery_time_id');
    }

    public function winners()
    {
        return $this->hasMany(TwoWinner::class, 'two_lucky_number_id');
    }

    public static function get_lucky_number($date, $time)
    {
        $data = TwoLuckyNumber::whereDate('date', $date)
                            ->where('lottery_time_id', $time)
                            ->where('status', 'Approved')
                            ->first();

        return $data ? $data->two_digit->number : "";
    }
}
