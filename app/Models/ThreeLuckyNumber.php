<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThreeLuckyNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'three_digit_id',
        'votes',
        'date',
        'status',
        'round'
    ];

    public function three_digit()
    {
        return $this->belongsTo(ThreeDigit::class, 'three_digit_id');
    }

    public function winners()
    {
        return $this->hasMany(ThreeWinner::class, 'three_lucky_number_id');
    }

    public static function last_data()
    {
        return self::where('status', 'Pending')->latest()->first();
    }

    public function current_round()
    {
        return self::last_data()->round;
    }

    public function current_date()
    {
        return self::last_data()->date;
    }

    public function comming_date()
    {
        return  Carbon::parse($this->current_date())
                      ->addDays(15)
                      ->format('Y-m-d');
    }

    public function add_new_round()
    {
        self::create([
            'round'  => $this->current_round() + 1,
            'date'   => $this->comming_date(),
            'status' => 'Pending'
        ]);
    }
}
