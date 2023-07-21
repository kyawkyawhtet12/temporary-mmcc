<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreeLuckyDraw extends Model
{
    use HasFactory;

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

    public function scopeFilterDates($query)
    {
        $date = explode(" - ", request()->input('from_to', ""));
        if (count($date) != 2) {
            $date = [now()->subDays(29)->format("Y-m-d"), now()->format("Y-m-d")];
        }
        return $query->whereBetween('created_at', $date);
    }
}
