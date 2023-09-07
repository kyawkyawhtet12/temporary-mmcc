<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentPaymentReport extends Model
{
    use HasFactory;

    protected $fillable = [ 'agent_id', 'deposit', 'withdraw' , 'net' ];

    public function getNetAmountAttribute()
    {
        return $this->deposit - $this->withdraw;
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
}
