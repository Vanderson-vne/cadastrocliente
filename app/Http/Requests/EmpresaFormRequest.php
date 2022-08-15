<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use sistemaweb\Http\Requests\Request;

class EmpresaFormRequest extends FormRequest
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
            'endereco'=>'max:100',
            'bairro'=>'max:50',
            'cidade'=>'max:50',
            'estado'=>'max:5',
            'cep'=>'max:10',
            'cnpj'=>'max:30',
            'responsavel'=>'max:100',
            'cpf'=>'max:45',
            'creci'=>'max:30',
            'email'=>'max:45',
            'banco_padrao_boleto'=>'max:45',
            'telefone'=>'max:45',
            'gera_todos_boletos'=>'max:20',
            'conta_caixa'=>'max:20',
            'transacao_caixa'=>'max:20',
        ];
    }
}
