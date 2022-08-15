<?php

namespace App\Imports;

use App\Proprietario;
use Maatwebsite\Excel\Concerns\ToModel;

class ProprietarioImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
      //  dd($row);

        return new proprietario([
                'idmunicipio'=> $row[0],
                'tipo_pessoa'=> $row[1],
                'nome'=> $row[2],
                'fantasia'=> $row[3],
                'fisica_juridica'=> $row[4],
                'cpf_cnpj'=> $row[5],
                'endereco'=> $row[6],
                'telefone'=> $row[7],
                'email'=> $row[8],
                'complemento_end'=> $row[9],
                'bairro'=> $row[10],
                'cidade'=> $row[11],
                'uf'=> $row[12],
                'cep'=> $row[13],
                'referencia'=> $row[14],
                'obs_prop'=> $row[15],
                'rg_ie'=> $row[16],
                'condicao'=> $row[17],
                'conjuge'=> $row[18],
                'aos_cuidados'=> $row[19],
                'end_corr'=> $row[20],
                'num_corr'=> $row[21],
                'compl_corr'=> $row[22],
                'bairro_corr'=> $row[23],
                'cidade_corr'=> $row[24],
                'uf_corr'=> $row[25],
                'cep_corr'=> $row[26],
                'favorecido'=> $row[27],
                'cpf_fav'=> $row[28],
                'banco_fav'=> $row[29],
                'ag_fav'=> $row[30],
                'conta_fav'=> $row[31],
                'estodo_civil'=> $row[32],
        ]);
    }
}
