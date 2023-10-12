<?php

namespace App\Http\Resources\BettingRecord\Collection;

use Illuminate\Http\Resources\Json\JsonResource;

class MaungCollection extends JsonResource
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
            'betting'  => $this->betting_type,
            'odds'   => $this->betting_fees,
            'amount' => '-',
            'result' => $this->result_status,
            'match' => $this->match->match_format,
        ];
    }
}
