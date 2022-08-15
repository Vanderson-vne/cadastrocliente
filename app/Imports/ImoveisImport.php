<?php

namespace App\Imports;

use App\Proprietario;
use App\Imovel;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;

class ImoveisImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $nome_prop = ([$row[0],]);

        $proprietarios=DB::table('proprietario')
        ->where('nome', '=', $nome_prop[0])
        ->get();

        $idprop = 0;
        $idprop = $proprietarios[0]->idproprietario;

        //dd($nome_prop,$idprop);

        //if ($idprop) {
            return new Imovel([
                'idproprietario'=> $row[0], //$idprop,
                'idmunicipio'=> $row[1],
                'nome'=> $row[2],
                'endereco'=> $row[3],
                'complemento'=> $row[4],
                'bairro'=> $row[5],
                'cidade'=> $row[6],
                'uf'=> $row[7],
                'cep'=> $row[8],
                'condicao'=> $row[9],
                'situacao'=> $row[10],
                'codigo'=> $row[11],
            ]); 
        //}
        
    }
}
