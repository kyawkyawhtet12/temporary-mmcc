<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TwoDigitLimitAddRequest extends FormRequest
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
            'agent_id' => 'required|array',
            'type' => 'required',
            'numbers' => 'required_if:type,1|array',
            'frontNumbers' => 'required_if:type,2|array',
            'time_id' => 'required|array',
            'date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => '* Required',
            'numbers.required_if' => '* Required',
            'frontNumbers.required_if' => '* Required',
        ];
    }
}
