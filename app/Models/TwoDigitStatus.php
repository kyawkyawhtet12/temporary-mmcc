<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoDigitStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'two_digit_id',
        'two_digits',
        'status',
        'amount',
        'date'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTwoDigitsGroupAttribute()
    {
        return implode(' , ', json_decode($this->two_digits, true));
    }
}
