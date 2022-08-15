<?php

namespace App\Exports;

use App\Imovel;
use Maatwebsite\Excel\Concerns\FromCollection;

class ImoveisExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Imovel::all();
    }
}
