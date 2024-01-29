<?php

namespace App\Models;

use App\Traits\BalloneFeesResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FootballBodyFeeResult extends Model
{
    use HasFactory, BalloneFeesResult;

    protected $guarded = [];

    public function fees()
    {
        return $this->belongsTo(FootballBodyFee::class, 'fee_id');
    }

}
