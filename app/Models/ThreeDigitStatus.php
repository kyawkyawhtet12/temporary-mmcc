<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreeDigitStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'three_digit_id',
        'status',
        'amount',
        'date',
        'round'
    ];
}
