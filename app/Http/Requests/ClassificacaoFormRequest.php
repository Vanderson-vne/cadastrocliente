<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassificacaoFormRequest extends FormRequest
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
            'nome'=>'max:45',
            'agrupamento'=>'max:45',
            'pag_rec'=>'max:20',
        ];
    }
}
