<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use sistemaweb\Http\Requests\Request;

class ReajusteFormRequest extends FormRequest
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
            'idindice'=>'required',
            'mes_ano'=>'max:20',
            'mensal'=>'numeric',
            'bimestral'=>'numeric',
            'trimestral'=>'numeric',
            'quadrimestral'=>'numeric',
            'quintimestral'=>'numeric',
            'semestral'=>'numeric',
            'anual'=>'numeric',
            'bianual'=>'numeric',
        ];
    }
}
