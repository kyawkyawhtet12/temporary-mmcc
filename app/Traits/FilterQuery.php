<?php

namespace App\Traits;

trait FilterQuery
{
    public function scopeFilterAgent($query)
    {
        if(request()->input('agent_id')){
            $query->whereIn('agent_id', request()->input("agent_id"));
        }
    }

    public function scopeFilterUser($query)
    {
        if ($user_id = request()->input("user_id")) {
            $query->whereHas('user', function ($w) use ($user_id) {
                $w->where('user_id', $user_id);
            });
        }
    }

    public function scopeFilterPhone($query)
    {
        if(request()->input('phone')){
            $query->where('phone', request()->input("phone"));
        }
    }

    public function scopeFilterByDate($query)
    {
        if ($start_date = request()->input('start_date')) {
            $query->whereDate('created_at', '>=', $start_date);
        }

        if ($end_date = request()->input('end_date')) {
            $query->whereDate('created_at', '<=', $end_date);
        }
    }

    public function scopePending($query)
    {
        $query->where('status', 'Pending');
    }

    public function scopeFilterNotToday($query)
    {
        $query->whereDate('created_at', '!=', today());
    }
}
