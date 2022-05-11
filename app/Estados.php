<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{  
    protected $fillable = [
        'codigo_uf','uf', 'nome', 'latitude', 'longitude', 'regiao'
    ];
}
