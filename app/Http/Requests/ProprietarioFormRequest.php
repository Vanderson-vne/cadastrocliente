<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use sistemaweb\Http\Requests\Request;

class ProprietarioFormRequest extends FormRequest
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
            'idmunicipio'=>'',
            'tipo_pessoa'=>'max:20',
            'nome'=>'required|max:100',
            'fantasia'=>'max:100',
            'fisica_juridica'=>'required|max:15',
            'cpf_cnpj'=>'required|cpf_cnpj|max:30',
            'endereco'=>'max:100',
            'telefone'=>'max:200',
            'email'=>'required|email|max:45',
            'complemento_end'=>'max:100',
            'bairro'=>'max:40',
            'cidade'=>'max:40',
            'uf'=>'max:2',
            'cep'=>'max:20',
            'referencia'=>'max:256',
            'obs_prop'=>'max:512',
            'rg_ie'=>'max:20',
            'condicao'=>'max:20',
            'conjuge'=>'max:50',
            'aos_cuidados'=>'max:100',
            'end_corr'=>'max:100',
            'num_corr'=>'max:15',
            'compl_corr'=>'max:100',
            'bairro_corr'=>'max:45',
            'cidade_corr'=>'max:45',
            'uf_corr'=>'max:5',
            'cep_corr'=>'max:20',
            'favorecido'=>'max:100',
            'banco_fav'=>'max:45',
            'ag_fav'=>'max:10',
            'conta_fav'=>'max:45',
            'estodo_civil'=>'max:45',
        ];
    }
}
