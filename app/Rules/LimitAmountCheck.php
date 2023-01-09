<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\LimitAmount;
use Illuminate\Support\Facades\Auth;

class LimitAmountCheck implements Rule
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
        $limit_amount = Auth::user()->amount;
        $min_amount = LimitAmount::find(1)->min_amount;
        $max_amount = LimitAmount::find(1)->max_amount;
        return $value >= $min_amount && $value <= $max_amount && $value <= $limit_amount;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute exceeds current balance & must be between 100 MMK and 100000 MMK.';
    }
}
