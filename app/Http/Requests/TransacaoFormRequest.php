<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use sistemaweb\Http\Requests\Request;

class TransacaoFormRequest extends FormRequest
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
            'transacao'=>'required|max:45',
            'tipo'=>'max:20',
            'situacao'=>'max:20',
            'filial'=>'max:45',
            'conta'=>'max:45',
            'transacao_filial'=>'max:45',
    
        ];
    }
}
