<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballBodyFee extends Model
{
    use HasFactory;

    protected $guarded = [];

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
        if ($this->up_team == 1) {
            return $this->match->home->name;
        } elseif ($this->up_team == 2) {
            return $this->match->away->name;
        }
    }
}
