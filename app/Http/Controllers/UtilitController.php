<?php

namespace App\Http\Controllers;

use App\Inquilino;
use App\Proprietario;
use App\Imovel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ReciboFormRequest;
use App\Exports\InquilinosExport;
use App\Exports\ProprietariosExport;
use App\Exports\ImoveisExport;

use App\Imports\ProprietarioImport;
use App\Imports\InquilinosImport;
use App\Imports\ImoveisImport;
use App\Imports\FiadorImport;
use App\Imports\EventoImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Storage;

class UtilitController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){
    		return view('tabela.utilit.index');
    }

    public function store(Request $request){
        
        $fileprop = $request->file('arquivo_prop');
        $fileinq = $request->file('arquivo_inq');
        $fileimovel = $request->file('arquivo_imovel');
        $filefia = $request->file('arquivo_fia');
        $fileeve = $request->file('arquivo_eve');
        
        if ($fileprop) {
            //dd($request,$fileprop);
            Excel::import(new ProprietarioImport, $fileprop);
        }
        
        if ($fileinq) {
            //dd($request,$fileinq);
            Excel::import(new InquilinosImport, $fileinq);
        }
        
        if ($fileimovel) {
            //dd($request,$fileimovel);
            Excel::import(new ImoveisImport, $fileimovel);
        }
       
        if ($filefia) {
            //dd($request,$filefia);
            Excel::import(new FiadorImport, $filefia);
        }
        if ($fileeve) {
            //dd($request,$fileeve);
            Excel::import(new EventoImport, $fileeve);
        }

        return back()->withStatus('Excel arquivo importado com sucesso!');
    }

    public function exportProprietarios() 
    {
        return Excel::download(new ProprietariosExport, 'proprietario.xlsx',\Maatwebsite\Excel\Excel::XLSX);
    }
    public function exportImoveis() 
    {
        return Excel::download(new ImoveisExport, 'imovel.xlsx',\Maatwebsite\Excel\Excel::XLSX);
    }
    public function exportInquilinos() 
    {
        return Excel::download(new InquilinosExport, 'inquilino.xlsx',\Maatwebsite\Excel\Excel::XLSX);
    }


}
