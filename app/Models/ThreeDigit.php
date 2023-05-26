<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreeDigit extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'number',
        'status',
        'amount',
        'date'
    ];
}
