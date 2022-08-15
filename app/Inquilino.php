<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inquilino extends Model
{
    protected $table = 'inquilino';
    protected $primaryKey = 'idinquilino';

    protected $fillable = [

    	'idproprietario',
    	'idimovel',
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
        'favorecido',
        'cpf_fav',
        'banco_fav',
        'ag_fav',
        'conta_fav',
        'ult_extrato',
        'data_ult_extrato',
        'irrf',
        'locacao_encerada',
        'dt_enc_locacao',
        'ult_recibo',
        'idlocacao'
    ];

    protected $guarded = [];
}
