<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalheLocacao extends Model
{
    protected $table = 'detalhe_locacao';
    protected $primaryKey = 'iddetalhe_locacao';

    protected $fillable = [

        'idlocacao',
        'idevento',
        'complemento',
        'qtde',
        'valor',
        'mes_ano_det',
        'qtde_limite'

    ];

    protected $guarded = [];
}
