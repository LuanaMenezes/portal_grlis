<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nome','cnpj', 'cga', 'senha', 'qtd_socios', 'uniprofissional'
    ];
}
