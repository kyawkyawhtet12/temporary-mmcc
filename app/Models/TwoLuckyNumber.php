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
}
