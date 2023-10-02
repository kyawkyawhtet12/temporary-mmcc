<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreeDigitSetting extends Model
{
    use HasFactory;

    protected $fillable = [ 'date' , 'start_time' , 'end_time' , 'status' ];

    public function lucky_number()
    {
        return $this->hasOne(ThreeLuckyNumber::class, 'date_id');
    }

    public function draws()
    {
        return $this->hasMany(ThreeLuckyDraw::class, 'round');
    }

    public function win_draws()
    {
        return $this->hasMany(ThreeLuckyDraw::class, 'round')
                    ->where('three_digit_id', $this->lucky_number_id);
    }

    public function getLuckyNumberIdAttribute()
    {
        return $this->lucky_number?->three_digit_id;
    }

    public function getLuckyNumberFullAttribute()
    {
        return $this->lucky_number?->three_digit?->number;
    }

    public static function addNextLuckyDate($current_date)
    {
        $next_date = Carbon::parse($current_date)->addDays(15);

        $data = ThreeDigitSetting::create([
            'date'       => $next_date->format('Y-m-d'),
            'start_time' => today()->addDays(1),
            'end_time'   => $next_date->setTime(14, 45, 00)
        ]);

        $data->lucky_number()->create([ 'status' => 'Pending' ]);

    }
}
