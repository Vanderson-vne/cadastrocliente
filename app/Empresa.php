<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $primaryKey = 'idempresa';

    protected $fillable = [

    	'nome',
        'fantasia',
    	'endereco',
    	'bairro',
    	'cidade',
    	'estado',
    	'cep',
    	'cnpj',
    	'responsavel',
    	'cpf',
    	'creci',
    	'email',
    	'banco_padrao_boleto',
		'telefone',
		'gera_todos_boletos',
		'conta_caixa',
		'transacao_caixa'
    ];

    protected $guarded = [];
}
