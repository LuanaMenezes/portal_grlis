<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DataEmissaoXML implements Rule
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
        $someDate = new \DateTime($value);
        $now = new \DateTime();

        return !($someDate->diff($now)->days > 30);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A data de emissão do XML está superior a 30 dias.';
    }
}
