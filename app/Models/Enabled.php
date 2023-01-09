<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enabled extends Model
{
    use HasFactory;

    protected $fillable = [
        'two_thai_status',
        'two_dubai_status',
        'three_status',
    ];
}
