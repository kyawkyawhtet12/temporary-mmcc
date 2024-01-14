<?php

namespace App\Models;

use App\Traits\FootballMatchAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    use HasFactory, FootballMatchAttribute;

    protected $guarded = [];

    protected $with = [ 'home' , 'away' , 'matchStatus' ];

    // type - 0 Refund

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

    public function matchStatus()
    {
        return $this->hasOne(FootballMatchStatus::class, 'match_id');
    }

    public function bodyLimit()
    {
        return $this->belongsTo(FootballBodyLimitGroup::class, 'body_limit');
    }

    //

    public function bodyfees()
    {
        return $this->hasOne(FootballBodyFee::class, 'match_id')->where('status', '=', 1);
    }

    public function oldBodyfees()
    {
        return $this->hasMany(FootballBodyFee::class, 'match_id')->where('status', '=', 0);
    }

    public function allBodyfees()
    {
        return $this->hasMany(FootballBodyFee::class, 'match_id');
    }

    //

    public function maungfees()
    {
        return $this->hasOne(FootballMaungFee::class, 'match_id')->where('status', '=', 1);
    }

    public function oldMaungfees()
    {
        return $this->hasMany(FootballMaungFee::class, 'match_id')->where('status', '=', 0);
    }

    public function allMaungfees()
    {
        return $this->hasMany(FootballMaungFee::class, 'match_id');
    }

    //

    public function bodies()
    {
        return $this->hasMany(FootballBody::class, 'match_id');
    }

    public function maungs()
    {
        return $this->hasMany(FootballMaung::class, 'match_id');
    }

    public function pendingMaungs()
    {
        return $this->hasMany(FootballMaung::class, 'match_id')->where('status', 0);
    }

    //

    public function getBodyLimitGroupAttribute()
    {
        return $this->bodyLimit ?
                "<p> ( {$this->limit_name} ) </p>
                 <span class='mt-2'> {$this->limit_amount} </span>"
                : "";
    }

    public function getLimitNameAttribute()
    {
        return $this->bodyLimit?->name;
    }

    public function getLimitAmountAttribute()
    {
        return number_format($this->bodyLimit?->max_amount);
    }


}
