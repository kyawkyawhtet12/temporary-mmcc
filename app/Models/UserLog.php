<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $fillable = ['agent_id', 'user_id', 'operation', 'amount', 'start_balance', 'end_balance', 'remark'];
}
