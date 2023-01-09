<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreeDigitTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'three_digit_id',
        'amount',
    ];

    public function three_digit()
    {
        return $this->belongsTo(ThreeDigit::class, 'three_digit_id');
    }
}
