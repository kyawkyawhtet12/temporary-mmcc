<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoDigitTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'two_digit_id',
        'amount',
        'lottery_time_id',
    ];

    public function two_digit()
    {
        return $this->belongsTo(TwoDigit::class, 'two_digit_id');
    }
}
