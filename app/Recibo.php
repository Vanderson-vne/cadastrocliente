<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $table = 'recibo';
    protected $primaryKey = 'idrecibo';

    protected $fillable = [

        'idlocacao',
        'mes_ano',
        'dt_inicial',
        'dt_final',
        'contador_aluguel',
        'reajuste',
        'dt_vencimento',
        'dt_pagamento',
        'idremessa',
        'nosso_numero',
        'forma_pgto',
        'cheque',
        'banco',
        'praca',
        'dt_emissao',
        'dt_apresentacao',
        'emitente',
        'valor_pgto',
        'troco',
        'telefone',
        'obs',
        'total_aluguel',
        'estado',
        'codigo',
        'taxa_adm',
        'liquido',
        'idretorno',
        'idinquilino',
        'idproprietario',
        'idimovel',
        'idindice'

    ];

    protected $guarded = [];

    public function detalhes(){
        return $this->hasMany(DetalheRecibo::class,'idrecibo');
    }

    public function locacao(){
        return $this->belongsTo(Locacao::class,'idlocacao');
    }
}
