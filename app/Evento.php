<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'evento';
    protected $primaryKey = 'idevento';

    protected $fillable = [

		'nome',
        'comissao',
        'pede_qtde',
    	'unidade',
    	'tipo',
    	'indice_cc',
    	'irrf',
        'imp_recibo',
        'comissao_iptu',
        'libera_cc',
        'agrupamento',
        'boleto'
    ];

    protected $guarded = [];
}
