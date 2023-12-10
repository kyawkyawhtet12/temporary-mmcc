<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMaungFee extends Model
{
    use HasFactory;

    protected $guarded = [];

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
        return $this->hasOne(FootballMaungFeeResult::class, 'fee_id');
    }

    public function getUpteamNameAttribute()
    {
        return ($this->up_team == 1) ? $this->match->home->name : $this->match->away->name ;
    }

    public function getMatchStatusAttribute()
    {
        if( $this->match->calculate_maung  && $this->status == 0){
            return "done-old";
        }

        if( $this->match->calculate_maung){
            return "done";
        }

        if( $this->status == 0){
            return "old";
        }

        if( $this->match->type == 0 ){
            return "refund";
        }

        if( $this->match->check_active() )
        {
            return "time-old";
        }
    }

    public function getMatchFormatAttribute()
    {
        return $this->match->match_format;
    }

    public function get_result($result)
    {
        return ( $this->match->calculate_maung ) ? check_plus_format($result) : '-' ;
    }
}
