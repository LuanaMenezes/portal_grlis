<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operacao extends Model
{
    protected $table = 'operacoes';
    protected $fillable = [
        'razaosocial','numero', 'tipotitulo', 'vcto', 'vlrface', 'qtdetitulo', 'endop', 'ddd', 'telefone','bancooperacao', 'digitooperacao',
    ];
}
