<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashout extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'user_id',
        'payment_provider_id',
        'phone',
        'remark',
        'status',
        'agent_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provider()
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }

    public function scopeFilterDates($query)
    {
        $date = explode(" - ", request()->input('date_range', ""));
        if (count($date) != 2) {
            $date = [now()->today()->format("Y-m-d"), now()->format("Y-m-d")];
        }
        return $query->whereBetween('created_at', [$date['0'] . " 00:00:00", $date['1'] . " 23:59:59"]);
    }
}
