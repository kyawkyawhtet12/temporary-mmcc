<?php

namespace App\Http\Resources;

use App\Http\Resources\PaymentProviderResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CashoutResource extends JsonResource
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
            'payment_provider' => new PaymentProviderResource($this->provider),
            'amount' => $this->amount,
            'requested_phone' => $this->phone,
            'remark' => $this->remark,
            'status' => $this->status,
            'created_at' => $this->created_at->format('F j, Y, g:i A'),
        ];
    }
}
