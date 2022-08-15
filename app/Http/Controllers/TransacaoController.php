<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Banco;
use App\Transacao;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\TransacaoFormRequest;
use Illuminate\Support\Facades\DB;

class TransacaoController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

	/**
	 * Tabela de Transações
	 *
	 * @param Request $request
	 * @return void
	 */
    public function index(Request $request){

		$empresas=DB::table('empresa as emp')
        ->get();

    	if($request){
    		$query=trim($request->get('searchText'));
			$transacoes=DB::table('transacao as t')
			->join('empresa as e', 't.idempresa', '=', 'e.idempresa')
    		->where('transacao', 'LIKE', '%'.$query.'%')
            ->orderBy('idtransacao','desc')
    		->get();
    		return view('banco.transacao.index', [
				"transacoes"=>$transacoes,
				"empresas"=>$empresas,
				 "searchText"=>$query
    			]);
    	}
    }

    public function create(){

    	$empresas=DB::table('empresa')->get();
   	
        return view("banco.transacao.create",["empresas"=>$empresas]);
    }
 
    public function store(TransacaoFormRequest $request){
    	  $transacao = new transacao;
	      $transacao->idempresa=$request->get('idempresa');
          $transacao->transacao=$request->get('transacao');
		  $transacao->tipo=$request->get('tipo');
		  $transacao->situacao=$request->get('situacao');
		  $transacao->filial=$request->get('filial');
		  $transacao->conta=$request->get('conta');
		  $transacao->transacao_filial=$request->get('transacao_filial');
    	  $transacao->save();
    	return Redirect::to('banco/transacao');
    }

    public function show($id){
    	return view("banco.transacao.show", 
    		["transacao"=>Transacao::findOrFail($id)]);
    }


 	public function edit($id){

    	$empresas=DB::table('empresa')->get();
   	
		return view("banco.transacao.edit", 
			["transacao"=>Transacao::findOrFail($id),
			"empresas"=>$empresas
			]);
    }


    public function update(TransacaoFormRequest $request, $id){
    	  $transacao=transacao::findOrFail($id);
	  //dd($request,$transacao);
		  $transacao->idempresa=$request->get('idempresa');
          $transacao->transacao=$request->get('transacao');
		  $transacao->tipo=$request->get('tipo');
		  $transacao->situacao=$request->get('situacao');
		  $transacao->filial=$request->get('filial');
		  $transacao->conta=$request->get('conta');
		  $transacao->transacao_filial=$request->get('transacao_filial');
    	  $transacao->update();
    	return Redirect::to('banco/transacao');
    }

    public function destroy($id){
    	$transacao=transacao::findOrFail($id);
    	$transacao->delete();
    	return Redirect::to('banco/transacao');
    }
}
