<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use sistemaweb\Http\Requests\Request;

class LocacaoFormRequest extends FormRequest
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
            'idinquilino'=>'',
            'idproprietario'=>'',
            'idimovel'=>'',
            'idindice'=>'',
            'dt_inicial'=>'required',
            'dt_final'=>'required',
            'reajuste'=>'required',
            'contador_aluguel'=>'required',
            'reajuste_sobre'=>'required',
            'vencimento'=>'required',
            'taxa_adm'=>'required',
            'desocupacao'=>'date',
            'estado'=>'max:10',
            'mes_ano'=>'max:10',
            'idevento'=>'',
            'complemento'=>'max:45',
            'qtde'=>'',
            'valor'=>'',
            'mes_ano_det'=>'max:10',
            'qtde_limite'=>'',
            'dt_ini_contrato'=>'required',
            'dt_fin_contrato'=>'required',
            'codigo'=>'max:45',
            'todos_recibos'=>'max:20',

        ];
    }
}
