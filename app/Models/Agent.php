<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Model
{
    use HasFactory;

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

    public function getBodyPercentageAttribute()
    {
        $per = $this->body_limit ? $this->body_limit->percentage : 5 ;

        return $per / 100;
    }
}
