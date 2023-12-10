<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    use HasFactory;

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

    public function get_result($type)
    {
        $result = $this->calculate_body ? $this->body_temp_score : '' ;

        return $result;
    }

    public function check_active()
    {
        return ( $this->date_time < now() || $this->matchStatus?->all_close ) ;
    }

    public function check_action($type)
    {
        return ($type == 'body') ?
                (!$this->calculate_body && !$this->check_refund('body'))
            : (!$this->calculate_maung && !$this->check_refund('maung'));
    }

    public function check_delete()
    {
        return ($this->bodies_count == 0 && $this->maungs_count == 0 && $this->type == 1);
    }

    public function check_refund($type)
    {
        return ( $type == 'body') ? $this->matchStatus?->body_refund : $this->matchStatus?->maung_refund;
    }

    public function getStatus($type = 'all')
    {
        $statusAttr = [
            'body'  =>  $this->matchStatus?->body_close,
            'maung' =>  $this->matchStatus?->body_close,
            'all'   =>  $this->matchStatus?->all_close
        ];

        return ($statusAttr[$type]) ? 'open' : 'close';
    }

    public function getStatusColor($type = 'all')
    {
        $statusAttr = [
            'body'  =>  $this->matchStatus?->body_close,
            'maung' =>  $this->matchStatus?->body_close,
            'all'   =>  $this->matchStatus?->all_close
        ];

        return ($statusAttr[$type]) ? 'text-info' : 'text-danger';

    }
}
