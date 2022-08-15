<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Evento;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\EventoFormRequest;
use Illuminate\Support\Facades\DB;


class EventoController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

        if($request){
    		$query=trim($request->get('searchText'));
    		$eventos=DB::table('evento')
    		->where('nome', 'LIKE', '%'.$query.'%')
            ->orderBy('idevento','desc')
    		->get();
    		return view('tabela.evento.index', [
                "evento"=>$eventos, 
                "empresas"=>$empresas,
                "searchText"=>$query
    			]);
    	}
    }

    public function create(){
        return view("tabela.evento.create");
    }
 
    public function store(EventoFormRequest $request){
        $evento = new evento;
        $evento->nome=$request->get('nome');
        $evento->comissao=$request->get('comissao');
        $evento->pede_qtde=$request->get('pede_qtde');
        $evento->unidade=$request->get('unidade');
        $evento->tipo=$request->get('tipo');
        $evento->indice_cc=$request->get('indice_cc');
        $evento->irrf=$request->get('irrf');
        $evento->imp_recibo=$request->get('imp_recibo');
        $evento->comissao_iptu=$request->get('comissao_iptu');
        $evento->libera_cc=$request->get('libera_cc');
        $evento->agrupamento=$request->get('agrupamento');
        $evento->boleto=$request->get('boleto');
        $evento->save();
        return Redirect::to('tabela/evento');
    }


    public function show($id){
    	return view("tabela/evento.show",
    		["evento"=>evento::findOrFail($id)]);
    }

    public function edit($id){
    	return view("tabela/evento.edit",
    		["evento"=>evento::findOrFail($id)]);
    }            


    public function update(EventoFormRequest $request, $id){
        $evento=evento::findOrFail($id);
        $evento->nome=$request->get('nome');
        $evento->comissao=$request->get('comissao');
        $evento->pede_qtde=$request->get('pede_qtde');
        $evento->unidade=$request->get('unidade');
        $evento->tipo=$request->get('tipo');
        $evento->indice_cc=$request->get('indice_cc');
        $evento->irrf=$request->get('irrf');
        $evento->imp_recibo=$request->get('imp_recibo');
        $evento->comissao_iptu=$request->get('comissao_iptu');
        $evento->libera_cc=$request->get('libera_cc');
        $evento->agrupamento=$request->get('agrupamento');
        $evento->boleto=$request->get('boleto');
    	$evento->update();
    	return Redirect::to('tabela/evento');
    }


    public function destroy($id){
    	$evento=evento::findOrFail($id);
    	$evento->delete();
    	return Redirect::to('tabela/evento');
    }
}
