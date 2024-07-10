<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LotteryResultRepository
{

    public function executeResults($type)
    {
        return match ($type) {
            '2D' => $this->handle2D(),
            '3D' => $this->handle3D(),
        };
    }

    public function handle2D()
    {
        return Cache::remember('two_digit_results', 60, function () {

            $query = DB::table('two_lucky_numbers')
                ->join("two_digits", "two_digits.id", "=", "two_lucky_numbers.two_digit_id")
                ->where("two_lucky_numbers.status", "Approved")
                ->select(
                    'two_digits.number',
                    'two_lucky_numbers.date as date',
                    'two_lucky_numbers.lottery_time_id as time'
                )
                ->groupBy('time', 'number', 'date')
                ->get();

            return [
                '1' => $query->where('time', 1)->pluck('number', 'date'),
                '2' => $query->where('time', 2)->pluck('number', 'date')
            ];
        });
    }

    public function handle3D()
    {
        $query = DB::table('three_lucky_numbers')
            // ->where("status", "Approved")
            ->select(
                DB::raw('(SELECT number FROM three_digits WHERE three_digits.id = three_lucky_numbers.three_digit_id) as number'),
                DB::raw('DATE(updated_at) as date'),
                'date_id as round'
            );

        return [
            'number' => $query->clone()->pluck('number', 'round'),
            'date' => $query->clone()->pluck('date', 'round')
        ];
    }
}
