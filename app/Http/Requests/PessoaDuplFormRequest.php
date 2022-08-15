<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PessoaDuplFormRequest extends FormRequest
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
            'nome'=>'required|max:100',
            'fantasia'=>'max:100',
            'fisica_juridica'=>'required|max:15',
            'cpf_cnpj'=>'cpf_cnpj|max:30',
            'for_cli'=>'max:45',
            'endereco'=>'max:100',
            'telefone'=>'max:20',
            'email'=>'required|email|max:45',
            'complemento'=>'max:100',
            'bairro'=>'max:40',
            'cidade'=>'max:40',
            'uf'=>'max:2',
            'cep'=>'max:20',
            'referencia'=>'max:256',
            'obs'=>'max:512',
            'rg_ie'=>'max:20',
            'conjuge'=>'max:50',
            'cpf_conj'=>'max:45',
            'rg_conj'=>'max:45',
            'aos_cuidados'=>'max:100',
            'end_corr'=>'max:100',
            'num_corr'=>'max:15',
            'compl_corr'=>'max:100',
            'bairro_corr'=>'max:45',
            'cidade_corr'=>'max:45',
            'uf_corr'=>'max:5',
            'cep_corr'=>'max:20',
            'condicao'=>'max:20',
            'banco'=>'max:45',
            'agencia'=>'max:45',
            'conta'=>'max:45',
            'favorecido'=>'max:45',
            'cota'=>'',
            'comissao'=>'',
            'comissao_fat'=>'',
            'comissao_rec'=>'',
            'abate_desconto'=>'',
            'base_comissao'=>'',
    
        ];
    }
}
