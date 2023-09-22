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

    public function bodySetting()
    {
        return $this->hasOne(FootballBodySetting::class)
                    ->withDefault([
                        'min_amount' => 1000,
                        'max_amount' => 10000000,
                        'percentage' => 5
                    ]);
    }

    public function maungSetting()
    {
        return $this->hasOne(FootballMaungLimit::class)
                    ->withDefault([
                        'min_amount' => 500,
                        'max_amount' => 10000000,
                    ]);
    }

    public function two_limit()
    {
        return $this->hasOne(TwoLimitAmount::class)
                    ->withDefault([
                        'min_amount' => 100,
                        'max_amount' => 100000,
                    ]);
    }

    public function three_limit()
    {
        return $this->hasOne(ThreeLimitAmount::class)
                    ->withDefault([
                        'min_amount' => 100,
                        'max_amount' => 100000,
                    ]);
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

    public function users()
    {
        return $this->hasMany(User::class , 'referral_code', 'referral_code');
    }

    public function payment_reports()
    {
        return $this->hasMany(AgentPaymentReport::class, 'agent_id');
    }

    public function user_payment_reports()
    {
        return $this->users;
    }
}
