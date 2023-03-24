<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPaymentAllReport extends Model
{
    use HasFactory;

    protected $fillable = [ 'deposit', 'withdraw' , 'net' ];

    public function getNetAmountAttribute()
    {
        return $this->deposit - $this->withdraw;
    }

    public static function addReport($payment, $type)
    {
        $check = AgentPaymentAllReport::whereDate('created_at', today())
                                ->first();

        if ($check) {
            $check->increment($type, $payment->amount);
        } else {
            $check = AgentPaymentAllReport::create([
                $type      => $payment->amount
            ]);
        }
    }
}
