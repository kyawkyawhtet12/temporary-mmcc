<?php

namespace App\Models;

use App\Traits\FootballMatchAttribute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FootballMatch extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['home', 'away', 'league'];

    protected $appends = ['date_time_format', 'limit_amount'];

    // relationship
    use FootballMatchAttribute; // Ensure this trait is included


    public function matchStatus()
    {
        return $this->hasOne(FootballMatchStatus::class, 'match_id');
    }

    public function home()
    {
        return $this->belongsTo(Club::class, 'home_id');
    }

    public function away()
    {
        return $this->belongsTo(Club::class, 'away_id');
    }

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id');
    }

    public function bodyfees()
    {
        return $this->hasOne(FootballBodyFee::class, 'match_id')->where('status', '=', 1)->whereNotNull('body');
    }

    public function maungfees()
    {
        return $this->hasOne(FootballMaungFee::class, 'match_id')->where('status', '=', 1)->whereNotNull('body');
    }

    public function bodies()
    {
        return $this->hasMany(FootballBody::class, 'match_id');
    }

    public function maungs()
    {
        return $this->hasMany(FootballMaung::class, 'match_id');
    }

    public function bodiesByUser()
    {
        return $this->hasMany(FootballBody::class, 'match_id')->where('user_id', auth()->id());
    }

    public function bodyLimit()
    {
        return $this->belongsTo(FootballBodyLimitGroup::class, 'body_limit');
    }

    // Attribute

    public function getDateTimeFormatAttribute()
    {
        return Carbon::parse($this->date_time)->format('d-m-Y g:i A');
    }

    public function getMatchFormatAttribute()
    {
        return "{$this->home_team} Vs {$this->away_team}";
    }

    public function getHomeTeamAttribute()
    {
        return "({$this->home_no}) {$this->home?->name} {$this->other_status(1)}";
    }

    public function getAwayTeamAttribute()
    {
        return "({$this->away_no}) {$this->away?->name} {$this->other_status(2)}";
    }

    public function other_status($status)
    {
        return ($this->other == $status) ? '(N)' : '';
    }

    public function getBodyResultAttribute()
    {
        return $this->calculate_body ? $this->body_temp_score : '';
    }

    public function getMaungResultAttribute()
    {
        return $this->calculate_maung ? $this->maung_temp_score : '';
    }

    public function getLimitAmountAttribute()
    {
        return $this->bodyLimit?->max_amount;
    }

    public function getBodyPercentageAttribute()
    {
        return $this->bodyLimit?->percentage;
    }


    // function

    public function getBodyAmountByUser($type)
    {
        return $this->bodiesByUser->where('type', $type)->sum('bet.amount');
    }

    public function getBodyAmountByAll($type)
    {
        return $this->bodies->where('type', $type)->sum('bet.amount');
    }

    public function check_refund($type)
    {
        return ($type == 'maung') ? $this->matchStatus?->maung_refund
            : $this->matchStatus?->body_refund;
    }

    //

    public function getBettingType($type)
    {
        if ($type == 'home') return $this->home_team;
        if ($type == 'away') return $this->away_team;

        return $type;
    }
}
