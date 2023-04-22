<?php

namespace App\Models;

use App\Casts\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentDeposit extends Model
{
    use HasFactory;

    protected $fillable = [ 'agent_id' , 'payment_provider_id' , 'amount' , 'account' , 'remark' , 'transaction', 'status'];

    protected $casts = [
        'transaction' => Image::class,
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function provider()
    {
        return $this->belongsTo(AdminPaymentProvider::class, 'payment_provider_id');
    }
}
