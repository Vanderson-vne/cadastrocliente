<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Empresa;
use App\Banco;
use App\Transacao;
use App\HistoricoPadrao;
use App\Mov_Contas;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

use PDF;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class ExtratoController extends Controller
{
    public function __Construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

        $bancos=DB::table('banco as ban')
        ->get();

        $historicos=DB::table('historico_padraos as his')
        ->get();
       
                 
    	if($request){
    		$query=trim($request->get('searchText'));
            $mov_de_contas=DB::table('mov_contas as m')
            ->join('empresa as e', 'm.idempresa', '=', 'e.idempresa')
            ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->join('transacao as t', 'm.idtransacao', '=', 't.idtransacao')
            ->join('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
			->select('m.idmov_contas','b.codigo','b.nome','t.transacao','t.tipo',
			'h.codigo as cod_padrao','h.historico as hist_padrao','m.data','m.documento','m.valor','m.historico','m.compensado','m.parcial')
			//->where('mov_contas', 'LIKE', '%'.$query.'%')
            ->orderBy('idmov_contas','desc')
            ->get();
            
    		return view('reports.extrato.index', [
				"mov_de_contas"=>$mov_de_contas,
				"bancos"=>$bancos,
				"empresas"=>$empresas,
				 "searchText"=>$query
    			]);
    	}
    }

    public function pdfExtrato(Request $request){
    
        $data = Carbon::create('');

        $empresas=DB::table('empresa as emp')
        ->get();

		$historicos=DB::table('historico_padraos as his')
        ->get();

        $transacoes=DB::table('transacao as tra')
        ->get();
        
        $eventos = HistoricoPadrao::all();
        $grupos = HistoricoPadrao::select('agrupamento')->groupBy('agrupamento')->get();

        if($request){
             $query=trim($request->get('searchText'));
             $dtInicial=trim($request->get('dtVectoInicial'));
             $dtFinal=trim($request->get('dtVectoFinal'));
             $idbanco=trim($request->get('idbanco'));

             $bancos=DB::table('banco as ban')
             ->where('ban.idbanco','=', $idbanco)
             ->get();

             $saldo=$bancos[0]->saldo;
             
             if ($dtInicial!="") {
                $saldozero=1;
            }
            if ($dtInicial=="") {
                $saldozero=0;
                $dtInicial='2001-01-01';
            }
            if ($dtFinal=="") {
                $dtFinal='4001-01-01';
            }

            $mov_de_contas=DB::table('mov_contas as m')
            ->join('empresa as e', 'm.idempresa', '=', 'e.idempresa')
            ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->join('transacao as t', 'm.idtransacao', '=', 't.idtransacao')
            ->join('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
			->select('m.idmov_contas','b.codigo','b.nome','b.saldo','t.transacao','t.tipo',
			'h.codigo as cod_padrao','h.historico as hist_padrao','h.agrupamento','m.data','m.documento','m.valor','m.historico','m.compensado','m.parcial')
            ->where('m.data','<', $dtInicial)
            ->where('m.idbanco','=', $idbanco)
            ->orderBy('m.data','asc')
            ->get();

            /////////Pesquisa para pegar o totalde de Credito
            $mov_de_contas=DB::table('mov_contas as m')
            ->join('empresa as e', 'm.idempresa', '=', 'e.idempresa')
            ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->join('transacao as t', 'm.idtransacao', '=', 't.idtransacao')
            ->join('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
			->select('m.idmov_contas','b.codigo','b.nome','b.saldo','t.transacao','t.tipo',
			'h.codigo as cod_padrao','h.historico as hist_padrao','m.data','m.documento','m.valor','m.historico','m.compensado','m.parcial')
            ->whereBetween('m.data', [$dtInicial,$dtFinal])
            ->where('m.idbanco','=', $idbanco)
            ->where('t.tipo','=', 'Credito')
            ->orderBy('m.data','asc')
            ->get();
            $total_credito = $mov_de_contas->sum('valor');

            /////////Pesquisa para pegar o totalde de Debitos
            $mov_de_contas=DB::table('mov_contas as m')
            ->join('empresa as e', 'm.idempresa', '=', 'e.idempresa')
            ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->join('transacao as t', 'm.idtransacao', '=', 't.idtransacao')
            ->join('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
			->select('m.idmov_contas','b.codigo','b.nome','b.saldo','t.transacao','t.tipo',
			'h.codigo as cod_padrao','h.historico as hist_padrao','m.data','m.documento','m.valor','m.historico','m.compensado','m.parcial')
            ->whereBetween('m.data', [$dtInicial,$dtFinal])
            ->where('m.idbanco','=', $idbanco)
            ->where('t.tipo','=', 'Debito')
            ->orderBy('m.data','asc')
            ->get();
            $total_debito = $mov_de_contas->sum('valor');

            $mov_de_contas=DB::table('mov_contas as m')
            ->join('empresa as e', 'm.idempresa', '=', 'e.idempresa')
            ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->join('transacao as t', 'm.idtransacao', '=', 't.idtransacao')
            ->join('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
			->select('m.idmov_contas','b.codigo','b.nome','b.saldo','t.transacao','t.tipo',
			'h.codigo as cod_padrao','h.historico as hist_padrao','h.agrupamento','m.data','m.documento','m.valor','m.historico','m.compensado','m.parcial')
            ->whereBetween('m.data', [$dtInicial,$dtFinal])
            ->where('m.idbanco','=', $idbanco)
            ->orderBy('m.data','asc')
            ->get();
    
            $total_relatorio = $mov_de_contas->sum('valor');

            ////////////RECALCULO DE SALDO DE CONTAS CAIXA/BANCO
            if ($mov_de_contas->isEmpty()){
                $saldoRecalculo=0;
            } else {
                    $idloop=$mov_de_contas[0]->idmov_contas;
                    $contas = mov_contas::find($idloop);
                    
                    $trans=$contas->idtransacao;
                    $transacoes=DB::table('transacao as tra')
                    ->where('tra.idtransacao','=', $trans)
                    ->get();

                if ($transacoes[0]->tipo=="Credito") {
                    $saldoRecalculo = $mov_de_contas[0]->parcial - $mov_de_contas[0]->valor;
                }
                if ($transacoes[0]->tipo=="Debito") {
                    $saldoRecalculo = $mov_de_contas[0]->parcial + $mov_de_contas[0]->valor;
                }
                
            }
           if ($saldozero=='0'){
                $saldoRecalculo=0;
           }
           
        //    echo "         Começo Parcial: " . $mov_de_contas[0]->parcial . "</br>";
        //    echo "         Começo valor: " . $mov_de_contas[0]->valor . "</br>";
        //    echo "         Começo Saldo: " . $saldoRecalculo . "</br>";


           if ($mov_de_contas->isEmpty()){
            } else {
                foreach ($mov_de_contas as $mov) {
                    $idloop=$mov->idmov_contas;
                    $contas = mov_contas::find($idloop);
                    $valor = $contas->valor;

                    $trans=$contas->idtransacao;
                    $transacoes=DB::table('transacao as tra')
                    ->where('tra.idtransacao','=', $trans)
                    ->get();
                    
                    if ($transacoes[0]->tipo=="Credito") {
                        $contas->parcial=$saldoRecalculo + $valor;
                    }
                    if ($transacoes[0]->tipo=="Debito") {
                        $contas->parcial=$saldoRecalculo - $valor;
                    }
                    $contas->save();
                    $saldoRecalculo=$contas->parcial;

                    // echo "         Saldo Antes: " . $saldoRecalculo . "</br>";
                    // echo "         ====================== " ."</br>";                    

                    // echo " Chave " .$mov->idmov_contas. " - ";
                    // echo "         Valor: " . $mov->valor . "</br>";
                    // echo "         Saldo depois: " . $contas->parcial . "</br>";
                    // echo "         ====================== " ."</br>";                    
            }

                $bancos=DB::table('banco as ban')
                ->where('ban.idbanco','=', $idbanco)
                ->get();
                
                $mc_idbanco = DB::table('banco')
                ->where('idbanco', $idbanco)
                ->update([
                'saldo' => $saldoRecalculo,
               ]);
                
            }

//dd($mov_de_contas);

    //$total_saldo=$saldo-$total_debito+$total_credito;

        } //if($request){

        if ($total_relatorio) {
            $pdf = PDF::loadView('reports/extrato/pdf_extrato',compact('empresas','mov_de_contas','transacoes','total_relatorio','dtInicial','dtFinal','saldo','total_credito','total_debito','eventos','grupos'));
            return $pdf->setPaper('a4')->stream('todos_extrato.pdf');
        }

    }

}
