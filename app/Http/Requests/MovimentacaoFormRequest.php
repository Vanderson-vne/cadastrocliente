<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovimentacaoFormRequest extends FormRequest
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
            'idempresa'=>'',
            'idinquilino'=>'',
            'idproprietario'=>'',
            'idbanco'=>'',
            'idmov_contas'=>'',
            'idtransacao'=>'',
            'idevento'=>'',
            'idlocacao'=>'',
            'idrecibo'=>'',
            'conta'=>'max:20',
            'data'=>'required',
            'mes_ano'=>'max:10',
            'historico'=>'max:256',
            'documento'=>'max:45',
            'valor'=>'numeric',
            'complemento'=>'max:45',
            'comissao'=>'numeric',
            'incide_caixa'=>'max:10',
            'caixa_rec_pag'=>'max:10',
            'incide_conta_cor'=>'max:10',
            'Tipo_d_c'=>'max:10',
            'ult_extrato'=>'',
            'parcial'=>'',
            'idhistorico'=>'',

        ];
    }
}
