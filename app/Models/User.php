<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
// use Laravel\Passport\HasApiTokens;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use  HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'phone',
        'amount',
        'referral_code',
        'status',
        'password',
        'initial_password',
        'last_active',
        'clear_bindings',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getAgent()
    {
        $agent = Agent::where('referral_code', Auth::user()->referral_code)->first();
        return $agent ? $agent->id : null;
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'referral_code', 'referral_code');
    }

    public function cashoutPhone()
    {
        return $this->hasOne(UserPhone::class);
    }

    public function recharges()
    {
        return $this->hasMany(Payment::class, 'user_id')->latest();
    }

    public function cashouts()
    {
        return $this->hasMany(Cashout::class, 'user_id')->latest();
    }

    public function football_bets()
    {
        return $this->hasMany(FootballBet::class, 'user_id')->latest();
    }

    public function all_2d_draws()
    {
        return $this->hasMany(TwoLuckyDraw::class, "user_id");
    }

    public function today_2d_draws()
    {
        return $this->hasMany(TwoLuckyDraw::class, "user_id")->whereDate("created_at", today());
    }

    //

    public function two_min_amount()
    {
        return $this->agent->twoLimit->min_amount;
    }

    public function two_max_amount()
    {
        return $this->agent->twoLimit->max_amount;
    }

    public function three_min_amount()
    {
        return $this->agent->threeLimit->min_amount;
    }

    public function three_max_amount()
    {
        return $this->agent->threeLimit->max_amount;
    }

    // Ballone

    public function body_min_amount()
    {
        return $this->agent->bodySetting->min_amount;
    }

    public function body_max_amount()
    {
        return $this->agent->bodySetting->max_amount;
    }

    public function body_percentage()
    {
        return $this->agent->bodySetting->percentage;
    }

    public function maung_min_amount()
    {
        return $this->agent->maungSetting->min_amount;
    }

    public function maung_max_amount()
    {
        return $this->agent->maungSetting->max_amount;
    }

    public function getBannersAttribute()
    {
        return $this->agent->banners;
    }

    // 2d limit
    public function two_digit_limits_agent($time, $status = 1)
    {
        $limits = TwoDigitLimit::where("agent_id", auth()->user()->agent->id)
            ->where("date", today()->format('Y-m-d'))
            ->where("lottery_time_id", $time)
            ->where("status", $status)
            ->first();

        return  $limits ? json_decode($limits->number, true) : [];
    }

    public function two_digit_limits_all()
    {
        $limits = TwoDigitLimit::whereNull("agent_id")->first();
        return  $limits ? json_decode($limits->number, true) : [];
    }

    public function today_2d_transactions($time_id) // user transaction
    {
        $draws = Auth::user()->today_2d_draws()
            ->where('lottery_time_id', $time_id)
            ->selectRaw('SUM(amount) as amount, two_digit_id')
            ->groupBy('two_digit_id')
            ->get()
            ->pluck("amount", "twodigit.number");

        return $draws;
    }

    public function payment_reports()
    {
        return $this->hasMany(UserPaymentReport::class, 'user_id');
    }
}
