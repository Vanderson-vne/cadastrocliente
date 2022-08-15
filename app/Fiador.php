<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fiador extends Model
{
    protected $table = 'fiador';
    protected $primaryKey = 'idfiador';

    protected $fillable = [

    	'idinquilino',
    	'idmunicipio',
        'tipo_pessoa',
    	'nome',
        'fantasia',
        'fisica_juridica',
    	'cpf_cnpj',
    	'endereco',
    	'telefone',
    	'email',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'cep',
        'referencia',
        'obs',
        'rg_ie',
        'condicao',
        'conjuge',
        'aos_cuidados',
        'end_corr',
        'num_corr',
        'compl_corr',
        'bairro_corr',
        'cidade_corr',
        'uf_corr',
        'cep_corr',
        'cpf_conj',
        'rg_conj'
    ];

    protected $guarded = [];
}
