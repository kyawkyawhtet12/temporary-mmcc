<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPaymentReport extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_id' , 'agent_id', 'deposit', 'withdraw' , 'net' ];

    public function getNetAmountAttribute()
    {
        return $this->deposit - $this->withdraw;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public static function getDepositCount($agent_id, $date)
    {
        return UserPaymentReport::where('agent_id', $agent_id)
                ->whereDate('created_at', $date)
                ->where('deposit', '!=', 0)->count();
    }

    public static function getWithdrawCount($agent_id, $date)
    {
        return UserPaymentReport::where('agent_id', $agent_id)
                ->whereDate('created_at', $date)
                ->where('withdraw', '!=', 0)->count();
    }

    public function scopeFilterDates($query)
    {
        $date = explode(" - ", request()->input('date_range', ""));
        if (count($date) != 2) {
            $date = [now()->today()->format("Y-m-d"), now()->format("Y-m-d")];
        }
        return $query->whereBetween('created_at', [$date['0'] . " 00:00:00", $date['1'] . " 23:59:59"]);
    }

    public static function addReport($payment, $type)
    {
        $check = UserPaymentReport::whereDate('created_at', today())
                                ->where('user_id', $payment->user_id)
                                ->first();

        if ($check) {
            $check->increment($type, $payment->amount);
        } else {
            $check = UserPaymentReport::create([
                'user_id' => $payment->user_id,
                'agent_id' => auth()->id(),
                $type      => $payment->amount
            ]);
        }
    }
}
