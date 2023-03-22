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
}
