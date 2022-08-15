<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Tabela_ir;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\IrfFormRequest;
use Illuminate\Support\Facades\DB;

class IrfController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

    	if($request){
    		$query=trim($request->get('searchText'));
    		$tabela_irs=DB::table('tabela_irs')
    		->get();
    		return view('tabela.irf.index', [
                "tabela_irs"=>$tabela_irs,
                "empresas"=>$empresas,
                "searchText"=>$query
    			]);
    	}
    }

    public function create(){
        return view("tabela.irf.create");
    }
 
    public function store(IrfFormRequest $request){
        $tabela_ir = new tabela_ir;
        $tabela_ir->codigo=$request->get('codigo');
        $tabela_ir->faixa1=$request->get('faixa1');
        $tabela_ir->aliquota1=$request->get('aliquota1');
        $tabela_ir->deduzir1=$request->get('deduzir1');
        $tabela_ir->faixa2=$request->get('faixa2');
        $tabela_ir->aliquota2=$request->get('aliquota2');
        $tabela_ir->deduzir2=$request->get('deduzir2');
        $tabela_ir->faixa3=$request->get('faixa3');
        $tabela_ir->aliquota3=$request->get('aliquota3');
        $tabela_ir->deduzir3=$request->get('deduzir3');
        $tabela_ir->faixa4=$request->get('faixa4');
        $tabela_ir->aliquota4=$request->get('aliquota4');
        $tabela_ir->deduzir4=$request->get('deduzir4');
        $tabela_ir->faixa5=$request->get('faixa5');
        $tabela_ir->aliquota5=$request->get('aliquota5');
        $tabela_ir->deduzir5=$request->get('deduzir5');
        $tabela_ir->save();
        return Redirect::to('tabela/irf');
    }


    public function show($id){
    	return view("tabela/irf.show",
    		["tabela_ir"=>tabela_ir::findOrFail($id)]);
    }

    public function edit($id){
    	return view("tabela/irf.edit",
    		["tabela_ir"=>tabela_ir::findOrFail($id)]);
    }            


    public function update(IrfFormRequest $request, $id){
        $tabela_ir=tabela_ir::findOrFail($id);
        $tabela_ir->codigo=$request->get('codigo');
        $tabela_ir->faixa1=$request->get('faixa1');
        $tabela_ir->aliquota1=$request->get('aliquota1');
        $tabela_ir->deduzir1=$request->get('deduzir1');
        $tabela_ir->faixa2=$request->get('faixa2');
        $tabela_ir->aliquota2=$request->get('aliquota2');
        $tabela_ir->deduzir2=$request->get('deduzir2');
        $tabela_ir->faixa3=$request->get('faixa3');
        $tabela_ir->aliquota3=$request->get('aliquota3');
        $tabela_ir->deduzir3=$request->get('deduzir3');
        $tabela_ir->faixa4=$request->get('faixa4');
        $tabela_ir->aliquota4=$request->get('aliquota4');
        $tabela_ir->deduzir4=$request->get('deduzir4');
        $tabela_ir->faixa5=$request->get('faixa5');
        $tabela_ir->aliquota5=$request->get('aliquota5');
        $tabela_ir->deduzir5=$request->get('deduzir5');
    	$tabela_ir->update();
    	return Redirect::to('tabela/irf');
    }


    public function destroy($id){
    	$tabela_ir=tabela_ir::findOrFail($id);
    	$tabela_ir->delete();
    	return Redirect::to('tabela/irf');
    }
}
