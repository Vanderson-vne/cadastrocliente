<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use sistemaweb\Http\Requests\Request;

class EventoFormRequest extends FormRequest
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
            'nome'=>'max:100',
            'comissao'=>'max:10',
            'pede_qtde'=>'max:10',
            'unidade'=>'max:20',
            'tipo'=>'max:10',
            'indice_cc'=>'max:10',
            'irrf'=>'max:10',
            'imp_recibo'=>'max:10',
            'comissao_iptu'=>'max:10',
            'libera_cc'=>'max:10',
            'agrupamento'=>'max:45',
            'boleto'=>'max:10',
        ];
    }
}
