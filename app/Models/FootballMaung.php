<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMaung extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Status
    // 0 - Pending , 1 - Win , 2 - Lose ,
    // Refund 0 - false , 1 - true

    public function match()
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    public function fees()
    {
        return $this->belongsTo(FootballMaungFee::class, 'fee_id');
    }

    public function bet()
    {
        return $this->belongsTo(FootballMaungGroup::class, 'maung_group_id');
    }
}
