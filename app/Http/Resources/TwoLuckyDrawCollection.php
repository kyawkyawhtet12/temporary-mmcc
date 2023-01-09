<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TwoLuckyDrawCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            // 'userinfo' => new UserResource($this->user),
            'amount' => $this->amount,
            'two_digit_number' => $this->twodigit->number,
            'lottery_time' => $this->lottery_time,
            'date_time' => $this->created_at->format('d-m-Y, g:i:s A'),
        ];
    }
}
