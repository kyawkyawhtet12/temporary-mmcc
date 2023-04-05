<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    use HasFactory;

    protected $guarded = [];

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

    //

    public function bodyfee()
    {
        return $this->hasOne(FootballBodyFee::class, 'match_id');
    }

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

    public function getOtherStatusAttribute()
    {
        return $this->other == 1 ? '(N)' : '';
    }
}
