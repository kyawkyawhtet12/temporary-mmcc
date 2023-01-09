<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgentDepositResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'agent' => $this->agent->name,
            'payment_provider' => $this->payment->name,
            'amount' => $this->amount,
            'account' => $this->account,
            'transaction' => $this->transaction,
            'status' => $this->status == 0 ? 'pending' :
                        ($this->status == 1 ? 'accept' : 'reject')
        ];
    }
}
