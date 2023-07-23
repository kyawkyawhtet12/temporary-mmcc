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
        'status',
        'amount',
        'date'
    ];
}
