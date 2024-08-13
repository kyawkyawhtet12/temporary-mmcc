<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThreeLuckyDraw extends Model
{
    use HasFactory , SoftDeletes;

    public $table = 'three_lucky_draws';

    protected $fillable = [
        'user_id',
        'agent_id',
        'three_digit_id',
        'amount',
        'round',
        'za'
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    protected $appends = [ 'lucky_number' , 'number' ];

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function threedigit()
    {
        return $this->belongsTo(ThreeDigit::class, 'three_digit_id');
    }

    public function betting_record()
    {
        return $this->belongsTo(BettingRecord::class, 'betting_record_id');
    }

    public function lucky()
    {
        return $this->hasOne(ThreeLuckyNumber::class, 'date_id', 'round')->where('status','Approved');
    }

    public function winner()
    {
        return $this->hasone(ThreeWinner::class);
    }

    public function scopeFilterDates($query)
    {
        $date = explode(" - ", request()->input('from_to', ""));
        if (count($date) != 2) {
            $date = [now()->subDays(29)->format("Y-m-d"), now()->format("Y-m-d")];
        }
        return $query->whereBetween('created_at', $date);
    }

    public function getWinAmountAttribute()
    {
        return $this->amount * $this->za;
    }

    public function getLuckyNumberAttribute()
    {
        return $this->lucky?->three_digit->number ?? '-';
    }

    public function getNumberAttribute()
    {
        return $this->threedigit?->number;
    }

}
