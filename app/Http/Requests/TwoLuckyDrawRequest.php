<?php

namespace App\Http\Requests;

use App\Rules\LimitAmountCheck;
use Illuminate\Foundation\Http\FormRequest;

class TwoLuckyDrawRequest extends FormRequest
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
            'data' => ['required', 'array'],
            'data.*.digit' => ['required', 'string', 'min:2', 'max:2'],
            'data.*.amount' => ['required', 'numeric', new LimitAmountCheck],
        ];
    }
}
