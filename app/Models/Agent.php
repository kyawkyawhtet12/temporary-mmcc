<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Agent extends Authenticatable
{
    use HasFactory, HasApiTokens;

    // protected $guard = 'agent';

    protected $fillable = [
        'name',
        'phone',
        'referral_code',
        'amount',
        'percentage',
        'status',
        'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
