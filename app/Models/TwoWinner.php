<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoWinner extends Model
{
    use HasFactory;

    protected $fillable = [
        'two_lucky_number_id',
        'two_lucky_draw_id',
        'user_id',
        'agent_id'
    ];

    public function twoLuckyNumber()
    {
        return $this->belongsTo(TwoLuckyNumber::class, 'two_lucky_number_id');
    }

    public function twoLuckyDraw()
    {
        return $this->belongsTo(TwoLuckyDraw::class, 'two_lucky_draw_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
}
