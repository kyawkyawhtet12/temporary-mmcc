<?php

namespace App\Rules;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class LimitWithdraw implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $limit_amount = Auth::guard('agent')->user()->amount;
        return $value <= $limit_amount;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute you cannot withdraw funds due to insufficient balance.';
    }
}
