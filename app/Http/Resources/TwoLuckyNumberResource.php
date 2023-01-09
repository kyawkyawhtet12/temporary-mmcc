<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TwoLuckyNumberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'two_digit_number' => $this->two_digit->number,
            'lottery_time' => $this->lottery_time,
            'lottery_date' => $this->created_at->format('d-m-Y'),
        ];
    }
}
