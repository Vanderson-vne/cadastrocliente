<?php

namespace App\Imports;

use App\Fiador;
use App\Inquilino;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;

class FiadorImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
     
        $nome_inq = ([$row[0],]);

        $inquilinos=DB::table('inquilino')
        ->where('nome', '=', $nome_inq)
        ->get();

       // dd($inquilinos,$nome_inq);

        $idinq = $inquilinos[0]->idinquilino;        

        return new fiador([

            'idinquilino'=> $idinq,
            'idmunicipio'=> $row[1],
            'tipo_pessoa'=> $row[2],
            'nome'=> $row[3],
            'fisica_juridica'=> $row[4],
            'cpf_cnpj'=> $row[5],
            'endereco'=> $row[6],
            'complemento'=> $row[7],
            'bairro'=> $row[8],
            'cidade'=> $row[9],
            'uf'=> $row[10],
            'cep'=> $row[11],
            'rg_ie'=> $row[12],
            'condicao'=> $row[13],
            'conjuge'=> $row[14],
            'cpf_conj'=> $row[15],
            'rd_conj'=> $row[16],
        ]);
    }
}
