<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimitAmount extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_amount',
        'max_amount',
    ];
}
