<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArquivoTerceiro extends Model
{
    protected $fillable = [
         'path_arquivo', 'terceiro_id'
    ];
}
