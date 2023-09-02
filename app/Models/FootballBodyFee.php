<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballBodyFee extends Model
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
        return $this->hasOne(FootballBodyFeeResult::class, 'fee_id');
    }

    public function getUpteamNameAttribute()
    {
        // return ($this->up_team == 1) ? $this->match->home->name : $this->match->away->name ;
        return ($this->up_team == 1) ? $this->home_team->name : $this->away_team->name ;
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
            'id', // refers to id column on invoices table
            'id', // refers to id column on customers table
            'match_id', // refers to invoice_id column on credit_notes table
            'home_id' // refers to customer_id column on invoices table
        );
    }

    public function away_team()
    {
        return $this->hasOneThrough(
            Club::class,
            FootballMatch::class,
            'id', // refers to id column on invoices table
            'id', // refers to id column on customers table
            'match_id', // refers to invoice_id column on credit_notes table
            'home_id' // refers to customer_id column on invoices table
        );
    }
}
