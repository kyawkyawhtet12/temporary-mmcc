<?php

namespace App\Http\Resources;

use App\Models\ThreeDigitCompensation;
use Illuminate\Http\Resources\Json\JsonResource;

class ThreeWinnerCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $za = ThreeDigitCompensation::first();
        if ($this->status == "Full") {
            $money = $this->threeLuckyDraw->amount * $za->compensate;
            $votes = "Null";
        } else {
            $money = $this->threeLuckyDraw->amount * $za->vote;
            $votes = $this->threeLuckyDraw->threedigit->number;
        }
        return [
            'lucky_number' => $this->threeLuckyNumber->three_digit->number,
            'votes' => $votes,
            'amount' => $this->threeLuckyDraw->amount,
            'total' => $money,
            'status' => $this->status,
            'date' => $this->created_at->format('d-m-Y')
        ];
    }
}
