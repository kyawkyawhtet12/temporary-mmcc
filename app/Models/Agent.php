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

    public function two_limit()
    {
        return $this->hasOne(TwoLimitAmount::class);
    }

    public function three_limit()
    {
        return $this->hasOne(ThreeLimitAmount::class);
    }

    public function body_limit()
    {
        return $this->hasOne(FootballBodySetting::class);
    }

    public function maung_limit()
    {
        return $this->hasOne(FootballMaungLimit::class);
    }

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }
}
