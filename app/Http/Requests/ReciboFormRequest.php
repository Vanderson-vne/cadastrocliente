<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use sistemaweb\Http\Requests\Request;

class ReciboFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'idlocacao'=>'',
            'mes_ano'=>'max:10',
            'dt_inicial'=>'date',
            'dt_final'=>'date',
            'contador_aluguel'=>'numeric',
            'reajuste'=>'numeric',
            'dt_vencimento'=>'date',
            'dt_pagamento'=>'',
            'idevento'=>'',
            'complemento'=>'max:45',
            'qtde'=>'max:10',
            'valor'=>'',
            'mes_ano_det'=>'max:10',
            'qtde_limite'=>'max:10',
            'idremessa'=>'numeric',
            'nosso_numero'=>'max:45',
            'forma_pgto'=>'max:20',
            'cheque'=>'max:10',
            'banco'=>'max:10',
            'praca'=>'max:10',
            'dt_emissao'=>'',
            'dt_apresentacao'=>'',
            'emitente'=>'max:45',
            'valor_pgto'=>'numeric',
            'troco'=>'numeric',
            'telefone'=>'max:45',
            'obs'=>'max:100',
            'total_aluguel'=>'numeric',
            'estado'=>'max:20',
            'codigo'=>'max:45',
            'taxa_adm'=>'numeric',
            'liquido'=>'numeric',
            'idretorno'=>'numeric',
            'idinquilino'=>'numeric',
            'idproprietario'=>'numeric',
            'idimovel'=>'numeric',
            'idindice'=>'numeric',

        ];
    }
}
