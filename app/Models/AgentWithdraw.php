<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentWithdraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'agent_id',
        'payment_provider_id',
        'account',
        'remark',
        'status',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function provider()
    {
        return $this->belongsTo(AdminPaymentProvider::class, 'payment_provider_id');
    }
}
