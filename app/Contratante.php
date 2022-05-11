<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contratante extends Model
{
    protected $fillable = [
        'nome','cnpj', 'cedentecodigo'
    ];
}
