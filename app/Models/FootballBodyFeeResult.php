<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballBodyFeeResult extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fees()
    {
        return $this->belongsTo(FootballBodyFee::class, 'fee_id');
    }
}
