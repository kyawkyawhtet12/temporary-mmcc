<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoDigitClose extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id', 'user_id', 'date', 'lottery_time_id', 'status', 'admin_id'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id')
        ->withDefault([
            'name' => 'All Agent',
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')
        ->withDefault([
            'name' => 'All User',
        ]);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function time()
    {
        return $this->belongsTo(LotteryTime::class, 'lottery_time_id')
        ->withDefault([
            'time' => 'All Time',
        ]);
    }

    public function limit_numbers()
    {
        return $this->hasMany(TwoDigitLimitNumber::class, 'two_digit_close_id');
    }

    // public function getLimitNumberListsAttribute()
    // {
    //     return $this->limit_numbers()->pluck('number')->implode(" , ");
    // }

     public function getLimitNumberGroupAttribute()
    {
        return $this->limit_numbers->groupBy('amount');
    }
}
