<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{
    protected $fillable = [
        'Codigo','Tamanho', 'ValidaCMC7', 'Descricao'
    ];
}
