<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashoutFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment_provider_id' => 'required|numeric',
            'amount' => 'required|integer|between:1000,1000000',
            // 'phone' => 'required|phone:MM',
            'phone' => 'required',
            'remark' => 'required',
        ];
    }
}
