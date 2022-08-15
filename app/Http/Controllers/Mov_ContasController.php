<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Banco;
use App\Transacao;
use App\HistoricoPadrao;
use App\Mov_Contas;
use Carbon\Carbon;

use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Mov_ContasFormRequest;
use Illuminate\Support\Facades\DB;

class Mov_ContasController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
		$empresas=DB::table('empresa as emp')->get();

        $bancos=DB::table('banco as ban')->get();

        return view('banco.mov_contas.index', [
            "bancos"=>$bancos,
            "empresas"=>$empresas,
        ]);
    }

    public function all(Request $request)
    {
		$empresas=DB::table('empresa as emp')->get();

        $banco = Banco::where('idbanco',$request->banco_id)->first();

        $mov_de_contas=DB::table('mov_contas as m')
        ->join('empresa as e', 'm.idempresa', '=', 'e.idempresa')
        ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
        ->join('transacao as t', 'm.idtransacao', '=', 't.idtransacao')
        ->join('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
        ->select('m.idmov_contas','b.codigo','b.nome','t.transacao','t.tipo',
        'h.codigo as cod_padrao','h.historico as hist_padrao','m.data','m.documento','m.valor','m.historico','m.compensado','m.parcial')
        ->where('m.idbanco', $request->banco_id)
        ->orderBy('idmov_contas','desc')
        ->get();

        return view('banco.mov_contas.all', [
            "mov_de_contas"=>$mov_de_contas,
            "banco"=>$banco,
            "empresas"=>$empresas,
        ]);
    }


    public function create(){

    	$empresas=DB::table('empresa')->get();

        $bancos=DB::table('banco as ban')
        ->get();

        $transacoes=DB::table('transacao as tra')
        ->get();

        $historicos=DB::table('historico_padraos as his')
        ->get();

		return view("banco.mov_contas.create",[
			"transacoes"=>$transacoes,
			"bancos"=>$bancos,
			"historicos"=>$historicos,
			"empresas"=>$empresas]
		);
    }

    public function store(Mov_ContasFormRequest $request){

		$empresas=DB::table('empresa as emp')->get();
		$idempresa=$empresas[0]->idempresa;

		$bb=$request->get('idbanco');
		$bancos=DB::table('banco as ban')
		->where('ban.idbanco','=', $bb)
        ->get();

		$trans=$request->get('idtransacao');
        $transacoes=DB::table('transacao as tra')
		->where('tra.idtransacao','=', $trans)
        ->get();

		$valor=$request->get('valor');
		$comp=$request->get('compensado');
		if ($comp =='') {
			$comp="Nao";
		}

		//dd($request,$comp);

		if ($transacoes[0]->tipo=="Credito") {
			$total_saldo=$bancos[0]->saldo+$valor;
		}
		if ($transacoes[0]->tipo=="Debito") {
			$total_saldo=$bancos[0]->saldo-$valor;
		}

		  $mov_contas = new mov_contas;
	      $mov_contas->idempresa=$idempresa;
          $mov_contas->idbanco=$request->get('idbanco');
          $mov_contas->idtransacao=$request->get('idtransacao');
          $mov_contas->idhistorico=$request->get('idhistorico');
		  $mov_contas->data=$request->get('data').' '.Carbon::now()->toTimeString();
		  $mov_contas->documento=$request->get('documento');
		  $mov_contas->valor=$request->get('valor');
		  $mov_contas->historico=$request->get('historico');
		  $mov_contas->compensado=$comp; //$request->get('compensado');
		  $mov_contas->parcial=$total_saldo;  //$request->get('parcial');
		  $mov_contas->save();

		  $banco_up = DB::table('banco')
		  ->where('idbanco', $bb)
		  ->update([
		  'saldo' => $total_saldo,
	  		]);

		//Codigo para gerar Lançamento Dobrado
		 if ($transacoes[0]->transacao_filial != '') {
				$bb=$transacoes[0]->conta;
				$bancos=DB::table('banco as ban')
				->where('ban.idbanco','=', $bb)
				->get();

				$trans=$transacoes[0]->transacao_filial;
				$transacoes=DB::table('transacao as tra')
				->where('tra.idtransacao','=', $trans)
				->get();

				if ($transacoes[0]->tipo=="Credito") {
					$total_saldo=$bancos[0]->saldo+$valor;
				}
				if ($transacoes[0]->tipo=="Debito") {
					$total_saldo=$bancos[0]->saldo-$valor;
				}

				$mov_contas = new mov_contas;
				$mov_contas->idempresa=$bancos[0]->idempresa;
				$mov_contas->idbanco=$bancos[0]->idbanco;
				$mov_contas->idtransacao=$transacoes[0]->idtransacao;
				$mov_contas->idhistorico=$request->get('idhistorico');
				$mov_contas->data=$request->get('data').' '.Carbon::now()->toTimeString();
				$mov_contas->documento=$request->get('documento');
				$mov_contas->valor=$request->get('valor');
				$mov_contas->historico=$request->get('historico');
				$mov_contas->compensado=$comp; //$request->get('compensado');
				$mov_contas->parcial=$total_saldo;  //$request->get('parcial');
				$mov_contas->save();

				$banco_up = DB::table('banco')
				->where('idbanco', $bb)
				->update([
				'saldo' => $total_saldo,
					]);
	  		}


    	return Redirect::to('banco/mov_contas');
    }

    public function show($id){
    	return view("banco.mov_contas.show",
    		["mov_contas"=>mov_contas::findOrFail($id)]);
    }


 	public function edit($id){

    	$empresas=DB::table('empresa')->get();

        $bancos=DB::table('banco as ban')
		->get();

		$historicos=DB::table('historico_padraos as his')
        ->get();

		$mov_de_contas=DB::table('mov_contas as m')
		->join('empresa as e', 'm.idempresa', '=', 'e.idempresa')
		->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
		->join('transacao as t', 'm.idtransacao', '=', 't.idtransacao')
		->join('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
		->select('m.idmov_contas','m.idtransacao','b.codigo','b.idbanco','b.nome','b.saldo','t.transacao as nometransacao',
		'h.id as idhistorico','h.codigo as cod_padrao','h.historico as hist_padrao','t.tipo','m.data','m.documento','m.valor','m.historico','m.compensado','m.parcial')
		->where('m.idmov_contas','=', $id)
		->get();

        $transacoes=DB::table('transacao as tra')
		//->where('tra.idtransacao','=', $mov_de_contas[0]->idtransacao)
        ->get();

		return view("banco.mov_contas.edit",
			["mov_contas"=>mov_contas::findOrFail($id),
			"transacoes"=>$transacoes,
			"mov_de_contas"=>$mov_de_contas,
			"historicos"=>$historicos,
			"bancos"=>$bancos,
			"empresas"=>$empresas
			]);
    }


    public function update(Mov_ContasFormRequest $request, $id){

		$empresas=DB::table('empresa as emp')->get();
		$idempresa=$empresas[0]->idempresa;

		$mov_contas=mov_contas::findOrFail($id);
		$mov_contas->idempresa=$idempresa;
		$mov_contas->idbanco=$request->get('idbanco');
		$mov_contas->idtransacao=$request->get('idtransacao');
		$mov_contas->idhistorico=$request->get('idhistorico');
		$mov_contas->data=$this->parseDate($request->get('data')).' '.Carbon::now()->toTimeString();    //$request->get('data').' '.Carbon::now()->toTimeString();
		$mov_contas->documento=$request->get('documento');
		//$mov_contas->valor=$request->get('valor');
		$mov_contas->historico=$request->get('historico');
		$mov_contas->compensado=$request->get('compensado');
		//$mov_contas->parcial=$request->get('parcial');
		$mov_contas->update();
    	return Redirect::to('banco/mov_contas');
    }

    public function destroy($id){
    	$mov_contas=mov_contas::findOrFail($id);
    	$mov_contas->delete();
    	return Redirect::to('banco/mov_contas');
    }

	private function parseDate($date, $plusDay = false){
		if($plusDay == false)
			return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
		else
			return date('Y-m-d', strtotime("+1 day",strtotime(str_replace("/", "-", $date))));
	}
}
