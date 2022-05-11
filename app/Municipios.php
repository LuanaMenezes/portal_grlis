<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipios extends Model
{
    protected $fillable = [
                'codigo_ibge','uf', 'nome', 'latitude', 'longitude', 'capital','codigo_uf','siafi_id,', 'ddd', 'fuso_horario'
    ];
}
