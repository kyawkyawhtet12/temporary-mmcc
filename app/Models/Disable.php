<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disable extends Model
{
    use HasFactory;

    protected $fillable = [
        'datetime',
        'status',
    ];

    // public function setDateTimeAttribute($value)
    // {
    //     $this->attributes['datetime'] = date("Y-m-d H:i", strtotime($value);
    // }

    protected $casts = [
        'datetime' => 'datetime:Y-m-d H:i',
    ];
}
