<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\HistoricoPadrao;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\HistoricoFormRequest;
use Illuminate\Support\Facades\DB;

class HistoricoController extends Controller
{
    public function __Construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();
        

    	if($request){
    		$query=trim($request->get('searchText'));
            $historicos=DB::table('historico_padraos as h')
            ->select('h.id','h.codigo','h.historico','h.agrupamento')
            ->where('h.historico','LIKE', '%'.$query.'%')
    		->orderBy('h.id','desc')
    		->get();
    		return view('banco.historico.index', [
                "historicos"=>$historicos, 
                "empresas"=>$empresas,
                "searchText"=>$query
    		]);

    	}
    }

    public function create(){
        $empresas=DB::table('empresa as emp')
        ->get();


        return view("banco.historico.create",[
            "empresas"=>$empresas
            ]);
    }
 
    public function store(HistoricoFormRequest $request){

        $empresas=DB::table('empresa as emp')
        ->get();

        $historicos = new HistoricoPadrao;
        $historicos->idempresa=$request->get('idempresa');
        $historicos->codigo=$request->get('codigo');
        $historicos->historico=$request->get('historico');
        $historicos->agrupamento=$request->get('agrupamento');
        $historicos->save();
        return Redirect::to('banco/historico');
    }


    public function show($id){
    	return view("banco/historico.show",
    		["historicos"=>HistoricoPadrao::findOrFail($id)]);
    }

    public function edit($id){

        $empresas=DB::table('empresa as emp')
        ->get();

        return view("banco/historico.edit",
            ["historicos"=>HistoricoPadrao::findOrFail($id),
            "empresas"=>$empresas,
            ]);
    }            


    public function update(HistoricoFormRequest $request, $id){
    	$historicos=HistoricoPadrao::findOrFail($id);
        $historicos->idempresa=$request->get('idempresa');
        $historicos->codigo=$request->get('codigo');
        $historicos->historico=$request->get('historico');
        $historicos->agrupamento=$request->get('agrupamento');
    	$historicos->update();
    	return Redirect::to('banco/historico');
    }


    public function destroy($id){
    	$historicos=HistoricoPadrao::findOrFail($id);
    	$historicos->delete();
    	return Redirect::to('banco/historico');
    }    
}
