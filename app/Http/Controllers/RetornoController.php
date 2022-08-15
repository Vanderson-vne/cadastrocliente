<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Recibo;
use App\DetalheRecibo;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ReciboFormRequest;
use Illuminate\Support\Facades\DB;


use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class RetornoController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){
    
		$empresas=DB::table('empresa as emp')
        ->get();
        
        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->where('im.condicao','=','Ativo')->get();

        $indices=DB::table('indice as ind')->get();

    	if($request){
            $query=trim($request->get('searchText'));
    		$recibos = DB::table('recibo as r')
    		->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'l.idindice', '=', 'in.idindice')
    		->select('r.idretorno','r.idremessa','r.idrecibo','r.mes_ano','l.idlocacao', 'i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco', 'in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento')
            ->where('r.mes_ano', 'LIKE', '%'.$query.'%')
            ->orwhere('i.nome', 'LIKE', '%'.$query.'%')
            ->orwhere('p.nome', 'LIKE', '%'.$query.'%')
            ->orderBy('r.idrecibo','desc')
    		->get();
    		return view('banco.retorno.index', [
                "recibos"=>$recibos, 
                "empresas"=>$empresas,
                "searchText"=>$query
    			]);
    	}
    }

    public function edit($id){

        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->where('im.condicao','=','Ativo')->get();

        $indices=DB::table('indice as ind')->get();

        $eventos=DB::table('evento as eve')->get();

        $locacoes=DB::table('locacao as l')
        ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
        ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
        ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
        ->join('indice as in', 'l.idindice', '=', 'in.idindice')
        ->select('l.idlocacao', 'i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco', 'in.nome as nomeind','l.dt_inicial','l.dt_final','l.reajuste', 'l.contador_aluguel','l.reajuste_sobre', 'l.vencimento')
        ->where('l.estado','=','Ativo')
        ->get();

        $detalhes=DB::table('detalhe_recibo as d')
        ->join('evento as e','d.idevento','=','e.idevento')
        ->select('e.nome as evento', 'd.complemento', 'd.qtde', 'd.valor', 'd.mes_ano', 'd.qtde_limite')
        ->where('d.idrecibo','=', $id)
        ->get(); 

    	return view("tabela/recibo.edit",
            ["recibo"=>Recibo::findOrFail($id),
            "inquilinos"=>$inquilinos,
            "proprietarios"=>$proprietarios,
            "imoveis"=>$imoveis,
            "eventos"=>$eventos,
            "indices"=>$indices,
            "locacoes"=>$locacoes,
            "detalhes"=>$detalhes]);
    }            


    
}
