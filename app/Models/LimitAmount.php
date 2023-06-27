<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimitAmount extends Model
{
    use HasFactory;

    protected $fillable = [
        'two_min_amount',
        'two_max_amount',
        'three_min_amount',
        'three_max_amount'
    ];
}
