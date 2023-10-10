<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = [ 'home' , 'away' ];

    protected $appends = ['maung_result' , 'body_result'];

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

    //

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

    public function upteam_name($upteam)
    {
        return ($upteam == 1) ? $this->home?->name : $this->away?->name;
    }

    //

    public function body_score($key)
    {
        return self::getTempScore($this->body_temp_score)[$key];
    }

    public function maung_score($key)
    {
        return self::getTempScore($this->maung_temp_score)[$key];
    }

    public static function getTempScore($score)
    {
        return array_pad( explode("-", $score) , 2, '');
    }

    public function getMaungResultAttribute()
    {
        return $this->getResult('maung');
    }

    public function getBodyResultAttribute()
    {
        return $this->getResult('body');
    }

    public function getResult($type)
    {
        $attr = [
            'body'  => ($this->calculate_body)  ? $this->body_temp_score  : '-',
            'maung' => ($this->calculate_maung) ? $this->maung_temp_score : '-'
        ];

        return ($this->type == 0) ? "P-P" : $attr[$type];

    }
}
