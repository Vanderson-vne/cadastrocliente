<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinanceiroFormRequest extends FormRequest
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
            'classificacao_id'=>'required',
            'pessoa_dupls_id'=>'required',
            'duplicata'=>'max:45',
            'pagar_receber'=>'max:45',
            'tipo'=>'max:45',
            'nf'=>'',
            'data_nf'=>'',
            'valor'=>'required',
            'valor_liquido'=>'',
            'pgto_conta'=>'',
            'juros'=>'',
            'juros_dia'=>'',
            'desconto'=>'',
            'vencimento'=>'required',
            'pagamento'=>'',
            'nro_cheque'=>'',
            'apresentacao'=>'',
            'contabil'=>'',
            'lote'=>'',
            'historico'=>'',
        ];
    }
}
