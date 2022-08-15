<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $table = 'banco';
    protected $primaryKey = 'idbanco';

    protected $fillable = [

        'idempresa',
        'codigo',
        'nome',
        'agencia',
        'digito_agencia',
        'conta',
        'digito_conta',
        'carteira',
        'multa',
        'juros',
        'jurosapos',
        'prazo_protesto',
        'logo',
        'desc_demonstrativo1',
        'desc_demonstrativo2',
        'desc_demonstrativo3',
        'instrucao1',
        'instrucao2',
        'instrucao3',
        'codigo_cliente',
        'tipo_inscr_empresa',
        'nro_inscr_empresa',
        'cod_convenio_banco',
        'sequencia',
        'versao',
        'tipo_arquivo',
        'idremessa',
        'idretorno',
        'nosso_numero',
        'ambiente',
        'cod_cedente',
        'path_remessa',
        'path_retorno',
        'prefixo_cooperativa',
        'digito_prefixo',
        'especie_titulo',
        'saldo',
        'byte_idt',
        'posto',
        'situacao',
        'ultima_remessa',
        'idremessa',
        'contador_remessa'
    ];

    protected $guarded = [];
}
