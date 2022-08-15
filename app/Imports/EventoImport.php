<?php

namespace App\Imports;

use App\Evento;
use Maatwebsite\Excel\Concerns\ToModel;

class EventoImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new evento([
            'nome'=> $row[0],
            'comissao'=> $row[1],
            'pede_qtde'=> $row[2],
            'unidade'=> $row[3],
            'tipo'=> $row[4],
            'indice_cc'=> $row[5],
            'irrf'=> $row[6],
            'imp_recibo'=> $row[7],
            'comissao_iptu'=> $row[8],
            'agrupamento'=> $row[9],
        ]);
    }
}
