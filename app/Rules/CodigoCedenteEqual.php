<?php

namespace App\Rules;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;
use App\Contratante;

class CodigoCedenteEqual implements Rule
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
        $contratante = Contratante::select('cnpj')
        ->where('cedentecodigo', '=', Auth::user()->cedentecodigo)
        ->get();

        foreach($contratante as $c)
        {
            return $value == $c->cnpj;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O código cedente do XML deve ser igual ao do usuário.';
    }
}
