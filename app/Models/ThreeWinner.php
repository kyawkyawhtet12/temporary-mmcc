<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreeWinner extends Model
{
    use HasFactory;

    protected $fillable = [
        'three_lucky_number_id',
        'three_lucky_draw_id',
        'status',
    ];

    public function threeLuckyNumber()
    {
        return $this->belongsTo(ThreeLuckyNumber::class, 'three_lucky_number_id');
    }

    public function threeLuckyDraw()
    {
        return $this->belongsTo(ThreeLuckyDraw::class, 'three_lucky_draw_id');
    }
}
