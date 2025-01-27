<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'data',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }

    public function footballMatch()
    {
        return $this->belongsTo(FootballMatch::class, 'record_id')->with(['home', 'away', 'league']);
    }
}
