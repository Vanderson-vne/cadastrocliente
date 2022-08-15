<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use sistemaweb\Http\Requests\Request;

class Mov_ContasFormRequest extends FormRequest
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
            'idbanco'=>'',
            'idtransacao'=>'',
            'data'=>'',
            'documento'=>'max:45',
            'valor'=>'numeric',
            'historico'=>'max:256',
            'compensado'=>'max:10',
            'parcial'=>'numeric',
            'idhistorico'=>'',
            'idrecibo'=>'',
        ];
    }
}
