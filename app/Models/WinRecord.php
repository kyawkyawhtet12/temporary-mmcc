<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WinRecord extends Model
{
    use HasFactory;

    protected $fillable = ['agent_id','user_id','amount','type', 'betting_id', 'round' , 'status' ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
}
