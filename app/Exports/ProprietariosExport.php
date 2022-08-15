<?php

namespace App\Exports;

use App\Proprietario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProprietariosExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Proprietario::all();
    }
}

//class ProprietariosExport implements FromView
//{
    //public function view(): View
    //{
      //  return view('exports.proprietarios', [
      //      'proprietarios' => Proprietario::all()
      //  ]);
   // }
//}