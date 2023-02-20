<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMaungFeeResult extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fees()
    {
        return $this->belongsTo(FootballMaungFee::class, 'fee_id');
    }
}
