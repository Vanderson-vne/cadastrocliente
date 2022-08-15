<?php

namespace App\Exports;

use App\Inquilino;
use Maatwebsite\Excel\Concerns\FromCollection;

class InquilinosExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Inquilino::all();
    }

}