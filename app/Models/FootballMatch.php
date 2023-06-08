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

        // other - 0 => No , other - 1 => Home (N) , other - 2 => Away (N)

        return $this->other == 1 ? '(N)' : '';
    }

    public function getHomeOtherStatusAttribute()
    {

        // other - 0 => No , other - 1 => Home (N) , other - 2 => Away (N)

        return $this->other == 1 ? '(N)' : '';
    }

    public function getAwayOtherStatusAttribute()
    {

        // other - 0 => No , other - 1 => Home (N) , other - 2 => Away (N)

        return $this->other == 2 ? '(N)' : '';
    }

    public function getMatchFormatAttribute()
    {
        return "({$this->home_no}) " . $this->home?->name . " " .$this->home_other_status . " Vs " . "({$this->away_no}) " . $this->away?->name . " " . $this->away_other_status;
    }

    public function getHomeTempscoreAttribute()
    {
        return self::getTempScore($this->temp_score, 0);
    }

    public function getAwayTempscoreAttribute()
    {
        return self::getTempScore($this->temp_score, 1);
    }

    public static function getTempScore($score, $key)
    {
        if ($score) {
            $array = explode("-", $score);
            return str_replace(' ', '', $array[$key]);
        } else {
            return null;
        }
    }
}
