<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Banco;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\BancoFormRequest;
use Illuminate\Support\Facades\DB;


class BancoController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

	/**
	 * Tabela de Bancos 
	 *
	 * @param Request $request
	 * @return void
	 */
    public function index(Request $request){

		$empresas=DB::table('empresa as emp')
        ->get();

    	if($request){
    		$query=trim($request->get('searchText'));
    		$bancos=DB::table('banco')
    		->where('nome', 'LIKE', '%'.$query.'%')
            ->orderBy('idbanco','desc')
    		->get();
    		return view('banco.banco.index', [
				"bancos"=>$bancos,
				"empresas"=>$empresas,
				 "searchText"=>$query
    			]);
    	}
    }

    public function create(){

    	$empresas=DB::table('empresa')->get();
   	
        return view("banco.banco.create",["empresas"=>$empresas]);
    }
 
    public function store(BancoFormRequest $request){
    	  $banco = new banco;
	      $banco->idempresa=$request->get('idempresa');
          $banco->nome=$request->get('nome');
		  $banco->codigo=$request->get('codigo');
		  $banco->agencia=$request->get('agencia');
		  $banco->digito_agencia=$request->get('digito_agencia');
		  $banco->conta=$request->get('conta');
		  $banco->digito_conta=$request->get('digito_conta');
		  $banco->carteira=$request->get('carteira');
		  $banco->multa=$request->get('multa');
		  $banco->juros=$request->get('juros');
		  $banco->jurosapos=$request->get('jurosapos');
		  $banco->prazo_protesto=$request->get('prazo_protesto');
		  $banco->logo=$request->get('logo');
		  $banco->desc_demonstrativo1=$request->get('desc_demostrativo1');
		  $banco->desc_demonstrativo2=$request->get('desc_demostrativo2');
		  $banco->desc_demonstrativo3=$request->get('desc_demostrativo3');
		  $banco->instrucao1=$request->get('instrucao1');
		  $banco->instrucao2=$request->get('instrucao2');
		  $banco->instrucao3=$request->get('instrucao3');
		  $banco->codigo_cliente=$request->get('codigo_cliente');
		  $banco->tipo_inscr_empresa=$request->get('tipo_inscr_empresa');
		  $banco->nro_inscr_empresa=$request->get('nro_inscr_empresa');
		  $banco->cod_convenio_banco=$request->get('cod_convenio_banco');
		  $banco->sequencia=$request->get('sequencia');
		  $banco->versao=$request->get('versao');
		  $banco->tipo_arquivo=$request->get('tipo_arquivo');
		  $banco->idremessa=$request->get('idremessa');
		  $banco->idretorno=$request->get('idretorno');
		  $banco->nosso_numero=$request->get('nosso_numero');
		  $banco->ambiente=$request->get('ambiente');
		  $banco->cod_cedente=$request->get('cod_cedente');
		  $banco->path_remessa=$request->get('path_remessa');
		  $banco->path_retorno=$request->get('path_retorno');
		  $banco->prefixo_cooperativa=$request->get('prefixo_cooperativa');
		  $banco->digito_prefixo=$request->get('digito_prefixo');
		  $banco->especie_titulo=$request->get('especie_titulo');
		  $banco->saldo=$request->get('saldo');
		  $banco->byte_idt=$request->get('byte_idt');
		  $banco->posto=$request->get('posto');
		  $banco->situacao=$request->get('situacao');
    	  $banco->save();
    	return Redirect::to('banco/banco');
    }

    public function show($id){
    	return view("banco.banco.show", 
    		["banco"=>Banco::findOrFail($id)]);
    }


 	public function edit($id){

    	$empresas=DB::table('empresa')->get();
   	
		return view("banco.banco.edit", 
			["banco"=>banco::findOrFail($id),
			"empresas"=>$empresas
			]);
    }


    public function update(BancoFormRequest $request, $id){
    	  $banco=banco::findOrFail($id);
	      $banco->idempresa=$request->get('idempresa');
          $banco->nome=$request->get('nome');
		  $banco->codigo=$request->get('codigo');
		  $banco->agencia=$request->get('agencia');
		  $banco->digito_agencia=$request->get('digito_agencia');
		  $banco->conta=$request->get('conta');
		  $banco->digito_conta=$request->get('digito_conta');
		  $banco->carteira=$request->get('carteira');
		  $banco->multa=$request->get('multa');
		  $banco->juros=$request->get('juros');
		  $banco->jurosapos=$request->get('jurosapos');
		  $banco->prazo_protesto=$request->get('prazo_protesto');
		  $banco->logo=$request->get('logo');
		  $banco->desc_demonstrativo1=$request->get('desc_demostrativo1');
		  $banco->desc_demonstrativo2=$request->get('desc_demostrativo2');
		  $banco->desc_demonstrativo3=$request->get('desc_demostrativo3');
		  $banco->instrucao1=$request->get('instrucao1');
		  $banco->instrucao2=$request->get('instrucao2');
		  $banco->instrucao3=$request->get('instrucao3');
		  $banco->codigo_cliente=$request->get('codigo_cliente');
		  $banco->tipo_inscr_empresa=$request->get('tipo_inscr_empresa');
		  $banco->nro_inscr_empresa=$request->get('nro_inscr_empresa');
		  $banco->cod_convenio_banco=$request->get('cod_convenio_banco');
		  $banco->sequencia=$request->get('sequencia');
		  $banco->versao=$request->get('versao');
		  $banco->tipo_arquivo=$request->get('tipo_arquivo');
		  $banco->idremessa=$request->get('idremessa');
		  $banco->ultima_remessa=$request->get('ultima_remessa');
		  $banco->idretorno=$request->get('idretorno');
		  $banco->nosso_numero=$request->get('nosso_numero');
		  $banco->ambiente=$request->get('ambiente');
		  $banco->cod_cedente=$request->get('cod_cedente');
		  $banco->path_remessa=$request->get('path_remessa');
		  $banco->path_retorno=$request->get('path_retorno');
		  $banco->prefixo_cooperativa=$request->get('prefixo_cooperativa');
		  $banco->digito_prefixo=$request->get('digito_prefixo');
		  $banco->especie_titulo=$request->get('especie_titulo');
		  $banco->saldo=$request->get('saldo');
		  $banco->byte_idt=$request->get('byte_idt');
		  $banco->posto=$request->get('posto');
		  $banco->situacao=$request->get('situacao');
    	  $banco->update();
    	return Redirect::to('banco/banco');
    }

    public function destroy($id){
    	$banco=banco::findOrFail($id);
    	$banco->delete();
    	return Redirect::to('banco/banco');
    }
}
