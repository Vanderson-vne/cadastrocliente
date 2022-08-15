<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use sistemaweb\Http\Requests\Request;

class ImovelFormRequest extends FormRequest
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
            'idproprietario'=>'',
            'idmunicipio'=>'',
            'nome'=>'required|max:256',
            'endereco'=>'max:100',
            'complemento'=>'max:256',
            'bairro'=>'max:100',
            'cidade'=>'max:100',
            'uf'=>'max:5',
            'cep'=>'max:20',
            'referencia'=>'max:256',
            'obs'=>'max:512',
            'condicao'=>'max:20',
            'codigo'=>'max:45',
            'situacao'=>'max:45',
            'idinquilino'=>'',
            'idlocacao'=>'',
            'pessoa_dupls_id'=>'',
            'tipo'=>'',
            'status'=>'',
            'condicao2'=>'',
            'area'=>'',
            'area_construida'=>'',
            'quartos'=>'',
            'banheiros'=>'',
            'suites'=>'',
            'garagens'=>'',
            'piscinas'=>'',
            'ano_imovel'=>'',
            'valor_locacao'=>'',
            'valor_venda'=>'',
            'iptu'=>'',
            'condiminio'=>'',
            'comissao_adm'=>'',
            'comissao_corretor'=>'',
            'validade'=>'',
            'dt_inicial'=>'',
            'dt_final'=>'',
            'dt_venda'=>'',
            'descricao_imovel'=>'',
            'img_principal'=>'',
            'img_banner'=>'',
            'img_planta'=>'',
            'url_youtube'=>'',
    
        ];
    }
}
