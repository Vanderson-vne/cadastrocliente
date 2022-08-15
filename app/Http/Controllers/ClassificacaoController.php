<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Classificacao;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ClassificacaoFormRequest;
use Illuminate\Support\Facades\DB;


class ClassificacaoController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

        if($request){
    		$query=trim($request->get('searchText'));
    		$classificacaos=DB::table('classificacaos')
    		->where('nome', 'LIKE', '%'.$query.'%')
            ->orderBy('nome','desc')
    		->get();

            return view('financeiro.classificacao.index', [
                "classificacao"=>$classificacaos, 
                "empresas"=>$empresas,
                "searchText"=>$query
    			]);
    	}
    }

    public function create(){
        return view("financeiro.classificacao.create");
    }
 
    public function store(ClassificacaoFormRequest $request){
        $classificacao = new classificacao;
        $classificacao->nome=$request->get('nome');
        $classificacao->agrupamento=$request->get('agrupamento');
        $classificacao->pag_rec=$request->get('pag_rec');
        $classificacao->save();
        return Redirect::to('financeiro/classificacao');
    }


    public function show($id){
    	return view("financeiro/classificacao.show",
    		["classificacao"=>classificacao::findOrFail($id)]);
    }

    public function edit($id){
    	return view("financeiro/classificacao.edit",
    		["classificacao"=>classificacao::findOrFail($id)]);
    }            


    public function update(ClassificacaoFormRequest $request, $id){
        $classificacao=classificacao::findOrFail($id);
        $classificacao->nome=$request->get('nome');
        $classificacao->agrupamento=$request->get('agrupamento');
        $classificacao->pag_rec=$request->get('pag_rec');
    	$classificacao->update();
    	return Redirect::to('financeiro/classificacao');  
    }


    public function destroy($id){
    	$classificacao=classificacao::findOrFail($id);
    	$classificacao->delete();
    	return Redirect::to('financeiro/classificacao');
    }
}
