<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoDigitLimitNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'two_digit_close_id', 'number' , 'amount' , 'status'
    ];

    public function close()
    {
        return $this->belongsTo(TwoDigitClose::class, 'two_digit_close_id');
    }
}
