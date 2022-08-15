<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoaDupl extends Model
{
    protected $table = 'pessoa_dupls';
    protected $primaryKey = 'id';

    protected $fillable = [

    	'nome',
        'fantasia',
        'fisica_juridica',
    	'cpf_cnpj',
        'for_cli',
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
        'rg_conj',
        'condicao',
        'banco',
        'agencia',
        'conta',
        'favorecido',
        'cota',
        'comissao',
        'comissao_fat',
        'comissao_rec',
        'abate_desconto',
        'base_comissao',
    ];

    protected $guarded = [];
}