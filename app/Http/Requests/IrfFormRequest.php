<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IrfFormRequest extends FormRequest
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
            'codigo'=>'',
            'faixa1'=>'',
            'aliquota1'=>'',
            'deduzir1'=>'',
            'faixa2'=>'',
            'aliquota2'=>'',
            'deduzir2'=>'',
            'faixa3'=>'',
            'aliquota3'=>'',
            'deduzir3'=>'',
            'faixa4'=>'',
            'aliquota4'=>'',
            'deduzir4'=>'',
            'faixa5'=>'',
            'aliquota5'=>'',
            'deduzir5'=>'',
        ];
    }
}
