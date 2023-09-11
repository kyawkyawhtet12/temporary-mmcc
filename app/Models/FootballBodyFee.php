<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballBodyFee extends Model
{
    use HasFactory;

    protected $guarded = [];

    // protected $with = [ 'match' ];

    protected $appends = [ 'match_format' ];

    public function match()
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, 'by');
    }

    public function result()
    {
        return $this->hasOne(FootballBodyFeeResult::class, 'fee_id');
    }

    public function getUpteamNameAttribute()
    {
        return ($this->up_team == 1) ? $this->match->home->name : $this->match->away->name ;
    }

    public function getMatchStatusAttribute()
    {
        if( $this->match->calculate_body  && $this->status == 0){
            return "done-old";
        }

        if( $this->match->calculate_body){
            return "done";
        }

        if( $this->status == 0){
            return "old";
        }

        if( $this->match->type == 0 ){
            return "refund";
        }

        if( $this->match->date_time < now() )
        {
            return "time-old";
        }
    }

    public function getMatchFormatAttribute()
    {
        return $this->match->match_format;
    }

    public function home_team()
    {
        return $this->hasOneThrough(
            Club::class,
            FootballMatch::class,
            'id',
            'id',
            'match_id',
            'home_id'
        );
    }

    public function away_team()
    {
        return $this->hasOneThrough(
            Club::class,
            FootballMatch::class,
            'id',
            'id',
            'match_id',
            'away_id'
        );
    }

    public function get_result($result)
    {
        return ( $this->match->calculate_body ) ? check_plus_format($result) : '-' ;
    }
}
