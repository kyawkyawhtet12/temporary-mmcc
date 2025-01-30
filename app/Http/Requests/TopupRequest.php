<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopupRequest extends FormRequest
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
            'amount' => 'required|string|max:255',
            // 'phone' => 'required|phone:MM',
            'phone' => 'required|digits_between:8,15',
            // 'transation_no' => 'required|digits:6',
            'transation_ss' => 'nullable|mimes:jpg,jpeg,png'
        ];
    }

    public function messages()
    {
        return[
            'phone.digits_between' => '*Invalid Phone Number',
        ];
    }
}
