<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\PlanoConta;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\PlanoContasFormRequest;
use Illuminate\Support\Facades\DB;

class PlanoContasController extends Controller
{
    public function __Construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

    	if($request){
    		$query=trim($request->get('searchText'));
    		$planoContas=DB::table('plano_contas')
    		->where('conta','LIKE', '%'.$query.'%')
    		->orderBy('codigo')
    		->get();
    		return view('financeiro.planocontas.index', [
                "planocontas"=>$planoContas, 
                "empresas"=>$empresas,
                "searchText"=>$query
    		]);

    	}
    }

    public function create(){
        $empresas=DB::table('empresa as emp')
        ->get();

        return view("financeiro.planocontas.create",["empresas"=>$empresas]);
    }
 
    public function store(PlanoContasFormRequest $request){
        $empresas=DB::table('empresa as emp')
        ->get();

        $planoContas = new planoConta;
        $planoContas->idempresa=$request->get('idempresa');
        $planoContas->codigo=$request->get('codigo');
        $planoContas->conta=$request->get('conta');
        $planoContas->agrupamento=$request->get('agrupamento');
        $planoContas->save();
        return Redirect::to('financeiro/planocontas');
    }


    public function show($id){
    	return view("financeiro/planocontas.show",
    		["planoContas"=>planoConta::findOrFail($id)]);
    }

    public function edit($id){
        $empresas=DB::table('empresa')->get();

    	return view("financeiro/planocontas.edit",
            ["planoconta"=>planoconta::findOrFail($id),
            "empresas"=>$empresas
            ]);
    }            


    public function update(PlanoContasFormRequest $request, $id){
        $empresas=DB::table('empresa as emp')
        ->get();

        $planoContas=planoConta::findOrFail($id);
        $planoContas->idempresa=$request->get('idempresa');
        $planoContas->codigo=$request->get('codigo');
        $planoContas->conta=$request->get('conta');
        $planoContas->agrupamento=$request->get('agrupamento');
    	$planoContas->update();
    	return Redirect::to('financeiro/planocontas');
    }


    public function destroy($id){
    	$planoContas=planoConta::findOrFail($id);
    	$planoContas->delete();
    	return Redirect::to('financeiro/planocontas');
    }    
}
