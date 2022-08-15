<?php

namespace App\Imports;

use App\Proprietario;
use App\Inquilino;
use App\Imovel;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;

class InquilinosImport implements ToModel 
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $nome_prop = ([$row[0],]);
        $nome_imovel = ([$row[1],]);

        //dd($nome_prop,$nome_imovel);

        $proprietarios=DB::table('proprietario')
        ->where('nome', '=', $nome_prop)
        ->get();

        $imoveis=DB::table('imovel')
        ->where('endereco', '=', $nome_imovel)
        ->get();

        $idprop = $proprietarios[0]->idproprietario;        
        $idimov = $imoveis[0]->idimovel;

        return new inquilino([
            'idproprietario' => $idprop,
            'idimovel' => $idimov,
            'idmunicipio' => $row[2],
            'tipo_pessoa' => $row[3],
            'nome' => $row[4],
            'fantasia' => $row[5],
            'fisica_juridica'=> $row[6],
            'cpf_cnpj'=> $row[7],
            'rg_ie'=> $row[8],
            'conjuge'=> $row[9],
            'telefone'=> $row[10],
            'condicao'=> $row[11],
            ]);
    }
}
