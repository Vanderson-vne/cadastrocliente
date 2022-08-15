<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Proprietario extends Model
{
    protected $table = 'proprietario';
    protected $primaryKey = 'idproprietario';

    protected $fillable = [

    	'idmunicipio',
        'tipo_pessoa',
    	'nome',
        'fantasia',
        'fisica_juridica',
    	'cpf_cnpj',
    	'endereco',
    	'telefone',
    	'email',
        'complemento_end',
        'bairro',
        'cidade',
        'uf',
        'cep',
        'referencia',
        'obs_prop',
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
        'estado_civil'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected $guarded = [];
}
