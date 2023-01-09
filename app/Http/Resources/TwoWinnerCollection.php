<?php

namespace App\Http\Resources;

use App\Models\TwoDigitCompensation;
use Illuminate\Http\Resources\Json\JsonResource;

class TwoWinnerCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $za = TwoDigitCompensation::first();
        return [
            'lucky_number' => $this->twoLuckyNumber->two_digit->number,
            'lottery_time' => $this->twoLuckyNumber->lottery_time,
            'amount' => $this->twoLuckyDraw->amount,
            'total' => $this->twoLuckyDraw->amount * $za->compensate,
            'date' => $this->created_at->format('d-m-Y')
        ];
    }
}
