<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imovel extends Model
{
    protected $table = 'imovel';
    protected $primaryKey = 'idimovel';

    protected $fillable = [

        'idproprietario',
        'idmunicipio',
        'nome',
        'endereco',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'cep',
        'referencia',
        'obs',
        'condicao',
        'codigo',
        'situacao',
        'idinquilino',
        'idlocacao',
        'pessoa_dupls_id',
        'tipo',
        'status',
        'condicao2',
        'area',
        'area_construida',
        'quartos',
        'banheiros',
        'suites',
        'garagens',
        'piscinas',
        'ano_imovel',
        'valor_locacao',
        'valor_venda',
        'iptu',
        'condiminio',
        'comissao_adm',
        'comissao_corretor',
        'validade',
        'dt_inicial',
        'dt_final',
        'dt_venda',
        'descricao_imovel',
        'img_principal',
        'img_banner',
        'img_planta',
        'url_youtube',

    ];

    protected $guarded = [];
}
