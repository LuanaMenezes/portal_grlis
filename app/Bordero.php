<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bordero extends Model
{
    protected $fillable = [
        'codcedente',
        'dataop',
        'totalvlrface',
        'qtddigitada' ,
        'contratofomento' ,
        'contratante',
        'assinatura' ,
        'operacao',
        'nomebanco',
        'pixtipo',
        'pixchave',
        'numbanco',
        'agencia' ,
        'contacorrente',
        'cnpjcredito',
        'nome',
        'proposta'
    ];
    public function operacoes()
    {
        return $this->hasMany('App\Operacoes', 'bordero_id', 'id');
    }
}
