<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoDigitLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id', 'user_id', 'date', 'lottery_time_id', 'number' , 'amount' , 'status', 'admin_id'
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

    public function getLimitNumberGroupAttribute()
    {
        $array = json_decode($this->number);
        return collect($array)->sortKeys();
    }
}
