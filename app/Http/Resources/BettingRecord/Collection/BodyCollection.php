<?php

namespace App\Http\Resources\BettingRecord\Collection;

use Illuminate\Http\Resources\Json\JsonResource;

class BodyCollection extends JsonResource
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
            'betting'  => $this->body->betting_type,
            'odds'    => $this->body->betting_fees,
            'amount' => number_format($this->amount),
            'result' => $this->body->match->body_result,
            'match' => $this->body->match->match_format,
        ];
    }
}
