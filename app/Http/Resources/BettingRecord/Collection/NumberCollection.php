<?php

namespace App\Http\Resources\BettingRecord\Collection;

use Illuminate\Http\Resources\Json\JsonResource;

class NumberCollection extends JsonResource
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
            'betting' => $this->number,
            'odds'    => $this->za,
            'amount'  => number_format($this->amount),
            'result'  => $this->lucky_number
        ];
    }
}
