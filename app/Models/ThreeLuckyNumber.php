<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThreeLuckyNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'three_digit_id',
        'votes',
        'date',
        'status',
        'round'
    ];

    public function three_digit()
    {
        return $this->belongsTo(ThreeDigit::class, 'three_digit_id');
    }

    public function winners()
    {
        return $this->hasMany(ThreeWinner::class, 'three_lucky_number_id');
    }

    public function lottery()
    {
        return $this->belongsTo(ThreeDigitSetting::class, 'date_id');
    }
}
