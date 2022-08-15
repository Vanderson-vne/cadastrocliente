<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use sistemaweb\Http\Requests\Request;

class MunicipioFormRequest extends FormRequest
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
            'cep'=>'required|max:45',
            'nome'=>'required|max:100',
            'bairro'=>'required|max:100',
            'localidade'=>'required|max:100',
            'uf'=>'required|max:10',
        ];
    }
}
