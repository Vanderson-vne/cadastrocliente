<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use sistemaweb\Http\Requests\Request;

class BancoFormRequest extends FormRequest
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
            
          'idempresa'=>'required',
          'codigo'=>'required|max:20',
          'nome'=>'required|max:45',
          'agencia'=>'max:20',
          'digito_agencia'=>'max:10',
          'conta'=>'max:45',
          'digito_conta'=>'max:10',
          'carteira'=>'max:15',
          'multa'=>'numeric',
          'juros'=>'numeric',
          'jurosapos'=>'numeric',
          'prazo_protesto'=>'max:5',
          'logo'=>'max:45',
          'desc_demonstrativo1'=>'max:45',
          'desc_demonstrativo2'=>'max:45',
          'desc_demonstrativo3'=>'max:45',
          'instrucao1'=>'max:100',
          'instrucao2'=>'max:100',
          'instrucao3'=>'max:100',
          'codigo_cliente'=>'max:45',
          'tipo_inscr_empresa'=>'max:10',
          'nro_inscr_empresa'=>'max:45',
          'cod_convenio_banco'=>'max:45',
          'sequencia'=>'numeric',
          'versao'=>'max:10',
          'tipo_arquivo'=>'max:10',
          'idremessa'=>'numeric',
          'idretorno'=>'numeric',
          'nosso_numero'=>'max:45',
          'ambiente'=>'max:10',
          'cod_cedente'=>'max:10',
          'path_remessa'=>'max:45',
          'path_retorno'=>'max:45',
          'prefixo_cooperativa'=>'max:45',
          'digito_prefixo'=>'max:5',
          'especie_titulo'=>'max:10',
          'saldo'=>'numeric',
          'byte_idt'=>'max:10',
          'posto'=>'max:20',
          'situacao'=>'max:20',
          'ultima_remessa'=>'',
          'idremessa'=>'',
          'contador_remessa'=>'',
        ];
    }
}
