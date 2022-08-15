<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipio';
    protected $primaryKey = 'idmunicipio';

    protected $fillable = [

        'cep',
        'nome',
        'bairro',
        'localidade',
        'uf',
        'cod_pais'
    ];

    protected $guarded = [];
}
