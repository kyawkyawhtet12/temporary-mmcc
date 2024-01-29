<?php

namespace App\Http\Resources\BettingRecord;

use App\Http\Resources\BettingRecord\Collection\BodyCollection;
use App\Http\Resources\BettingRecord\Collection\MaungCollection;
use App\Http\Resources\BettingRecord\Collection\NumberCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BettingRecord\DetailCollection;

class DetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        $collections = match ($this->type) {
            "Body"  => BodyCollection::collection($this->ballone),
            "Maung" => MaungCollection::collection($this->ballone[0]->maung->teams),
            "2D"    => NumberCollection::collection($this->two_digit),
            "3D"    => NumberCollection::collection($this->three_digit),
            default => '',
        };

        return [
            'id'     => $this->id,
            'amount' => number_format($this->amount),
            'type'   => $this->type,
            'win_amount' => number_format($this->win_amount),
            'betting' => $collections
        ];
    }
}
