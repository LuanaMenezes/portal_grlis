<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sacado extends Model
{
    protected $table = 'sacados';
    protected $fillable = [
        'razao_social','cnpj', 'cep', 'endereco', 'bairro', 'cidade', 'estado', 'email', 'ddd','telefone', 'cedentecodigo', 'ativo'
    ];
}
