<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Movimentacao;
use App\Mov_Contas;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\MovimentacaoFormRequest;
use App\Inquilino;
use Illuminate\Support\Facades\DB;
use PDF;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class EmissaoChequesController extends Controller
{
    public function __Construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();
        
        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();
        
        $bancos=DB::table('banco as ban')
		->get();

    	if($request){
    		$query=trim($request->get('searchText'));
            $movimentacoes=DB::table('movimentacaos as m')
            ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
            ->leftjoin('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->join('transacao as t', 'm.idtransacao', '=', 't.idtransacao')
            ->select('m.id','m.data','m.valor','m.Tipo_d_c','m.historico','m.predatado','m.compensado','m.documento','m.nominal','m.idproprietario', 'p.nome as nomepro',
            'b.nome as nomeconta','b.idbanco','m.incide_caixa','m.incide_conta_cor','t.transacao','t.tipo')
            ->where('m.data','LIKE', '%'.$query.'%')
            ->where('m.tipo_lacto','=','Cheque')
    		->orderBy('m.id','desc')
    		->get();
    		return view('reports.emissao_cheques.index', [
                "movimentacoes"=>$movimentacoes, 
                "empresas"=>$empresas,
                "bancos"=>$bancos,
                "searchText"=>$query
    		]);

    	}
    }

    public function create(){
        $empresas=DB::table('empresa as emp')
        ->get();

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->orderBy('p.nome')
        ->get();

        $inquilinos=DB::table('inquilino as i')
        ->join('proprietario as p', 'i.idproprietario', '=', 'p.idproprietario')
        ->join('imovel as im', 'i.idimovel', '=', 'im.idimovel')
        ->select('i.idinquilino','i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco','im.situacao')
        ->orderBy('i.nome')
        ->where('i.condicao','=','Ativo')
        ->get();

        $bancos=DB::table('banco as ban')
        ->get();

        $eventos=DB::table('evento as eve')
        ->get();

        $mov_de_contas=DB::table('mov_contas as m')
		->join('empresa as e', 'm.idempresa', '=', 'e.idempresa')
		->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
		->join('transacao as t', 'm.idtransacao', '=', 't.idtransacao')
		->select('m.idmov_contas','m.idtransacao','b.codigo','b.idbanco','b.nome','b.saldo','t.transacao as nometransacao',
		't.tipo','m.data','m.documento','m.valor','m.historico','m.compensado','m.parcial')
		->get();

        $transacoes=DB::table('transacao as tra')
		//->where('tra.idtransacao','=', $mov_de_contas[0]->idtransacao)
        ->get();
        
        $plano_contas=DB::table('plano_contas as pla')
        ->orderBy('pla.codigo')
        ->get();

        $historicos=DB::table('historico_padraos as his')
        ->get();

        return view("banco.cheques.create",[
            "empresas"=>$empresas,
            "proprietarios"=>$proprietarios,
            "mov_de_contas"=>$mov_de_contas,
			"transacoes"=>$transacoes,
			"plano_contas"=>$plano_contas,
			"bancos"=>$bancos,
			"eventos"=>$eventos,
            "historicos"=>$historicos,
            "inquilinos"=>$inquilinos
            ]);
    }
 
    public function store(MovimentacaoFormRequest $request){

        $empresas=DB::table('empresa as emp')->get();
		$idempresa=$empresas[0]->idempresa;

        $r_prop=$request->get('idproprietario');
        if ($r_prop=='Selecione o Proprietário...') {
            $r_prop=Null;
        }

        $r_inq=$request->get('idinquilino');
        if ($r_inq=='Selecione o Inquilino...') {
            $r_inq=Null;
        }
        
        $r_plano_Contas=$request->get('idplano_conta');
        if ($r_plano_Contas=='Selecione o Plano de Contas...') {
            $r_plano_Contas=Null;
        }

        $r_eventos=$request->get('idevento');
        if ($r_eventos=='Selecione o Evento...') {
            $r_eventos=Null;
        }

        //dd($request,$r_prop);

        $movimentacao = new movimentacao;
        $movimentacao->idempresa=$idempresa;
        $movimentacao->idbanco=$request->get('idbanco');
        $movimentacao->idmov_contas=$request->get('idmov_contas');
        $movimentacao->idtransacao=$request->get('idtransacao');
        $movimentacao->idhistorico=$request->get('idhistorico');
        $movimentacao->conta=$request->get('conta');
        $movimentacao->data=$request->get('data');
        $movimentacao->predatado=$request->get('predatado');
        $movimentacao->historico=$request->get('historico');
        $movimentacao->nominal=$request->get('nominal');
        $movimentacao->documento=$request->get('documento');
        $movimentacao->valor=$request->get('valor');
        $movimentacao->Tipo_d_c='Debito';
        $movimentacao->incide_caixa='Nao';
        $movimentacao->incide_conta_cor='Sim';
        $movimentacao->caixa_rec_pag='Pagto';
        $movimentacao->tipo_lacto='Cheque';
        $movimentacao->save();

        //Movimentação Mov_contas
        $bb=$request->get('idbanco');
		$bancos=DB::table('banco as ban')
		->where('ban.idbanco','=', $bb)
        ->get();

		$trans=$request->get('idtransacao');
        $transacoes=DB::table('transacao as tra')
		->where('tra.idtransacao','=', $trans)
        ->get();

		$valor=$request->get('valor');
    	$comp="Nao";

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
		  $mov_contas->data=$request->get('data');
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
        
        return Redirect::to('banco/cheques');
    }


    public function show($id){
    	return view("banco/cheques.show",
    		["movimentacao"=>movimentacao::findOrFail($id)]);
    }

    public function edit($id){

        $empresas=DB::table('empresa as emp')->get();
		$idempresa=$empresas[0]->idempresa;

        $bancos=DB::table('banco as ban')
        ->get();

        $transacoes=DB::table('transacao as tra')
        ->get();

        $historicos=DB::table('historico_padraos as his')
        ->get();

        $movimentacoes=DB::table('movimentacaos as m')
        ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
        ->leftjoin('banco as b', 'm.idbanco', '=', 'b.idbanco')
        ->leftjoin('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
		->join('transacao as t', 'm.idtransacao', '=', 't.idtransacao')
		->select('m.id as id_mov','m.idmov_contas','m.idtransacao','b.codigo','b.idbanco as id_banco','b.nome','b.saldo','t.transacao as nometransacao',
        't.tipo','m.data','m.documento','m.valor','m.historico','m.compensado','m.parcial','m.nominal','m.idhistorico','h.codigo as cod_hist','h.historico as desc_hist')
        ->where('m.id','=', $id)
		->get();


        return view("banco/cheques.edit",
            ["movimentacao"=>movimentacao::findOrFail($id),
            "empresas"=>$empresas,
            "movimentacoes"=>$movimentacoes,
			"transacoes"=>$transacoes,
            "historicos"=>$historicos,
			"bancos"=>$bancos,
            ]);
    }            


    public function update(MovimentacaoFormRequest $request, $id){
        $empresas=DB::table('empresa as emp')->get();
		$idempresa=$empresas[0]->idempresa;

    	$movimentacao=movimentacao::findOrFail($id);
        $movimentacao->idempresa=$idempresa;
        $movimentacao->idbanco=$request->get('idbanco');
        $movimentacao->idmov_contas=$request->get('idmov_contas');
        $movimentacao->idtransacao=$request->get('idtransacao');
        $movimentacao->idhistorico=$request->get('idhistorico');
        $movimentacao->conta=$request->get('conta');
        $movimentacao->data=$request->get('data');
        $movimentacao->predatado=$request->get('predatado');
        $movimentacao->compensado=$request->get('compensado');
        $movimentacao->historico=$request->get('historico');
        $movimentacao->nominal=$request->get('nominal');
        $movimentacao->documento=$request->get('documento');
        $movimentacao->valor=$request->get('valor');
        $movimentacao->tipo_lacto='Cheque';
    	$movimentacao->update();
    	return Redirect::to('banco/cheques');
    }


    public function destroy($id){
    	$movimentacao=movimentacao::findOrFail($id);
    	$movimentacao->delete();
    	return Redirect::to('banco/cheques');
    }    

    public function print(Request $request){
    
        $empresas=DB::table('empresa as emp')
        ->get();

        $bancos=DB::table('banco as ban')
		->get();
		
		$historicos=DB::table('historico_padraos as his')
        ->get();

		
        if($request){

            $dtInicial=trim($request->get('dtVectoInicial'));
            $dtFinal=trim($request->get('dtVectoFinal'));
            $idinquilino_filtro=trim($request->get('idpinquilino'));
            $idproprietario_filtro=trim($request->get('idproprietario'));

            $query=trim($request->get('searchText'));
                      

            return view('reports.cheques.print', [
                "bancos"=>$bancos,
                 "searchText"=>$query
                ]);
        }
    }


    public function pdfEmissao_Cheques(Request $request){
    
        //dd($request);
        $data = Carbon::create('');

        $empresas=DB::table('empresa as emp')
        ->get();

        $bancos=DB::table('banco as ban')
		->get();
		
		$historicos=DB::table('historico_padraos as his')
        ->get();

        
        if($request){
             $query=trim($request->get('searchText'));
             $dtInicial=trim($request->get('dtVectoInicial'));
             $dtFinal=trim($request->get('dtVectoFinal'));
             $idbanco=trim($request->get('idbanco'));
        
            if ($dtInicial=="") {
                $dtInicial='2001-01-01';
            }
            if ($dtFinal=="") {
                $dtFinal='4001-01-01';
            }

            $mov_de_contas=DB::table('movimentacaos as m')
            ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
            ->leftjoin('inquilino as i', 'm.idinquilino', '=', 'i.idinquilino')
            ->leftjoin('plano_contas as c', 'm.idplano_conta', '=', 'c.id')
            ->leftjoin('evento as e', 'm.idevento', '=', 'e.idevento')
            ->leftjoin('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
            ->select('b.codigo as cod_banco','m.idbanco','b.nome as nomeconta','m.idplano_conta','c.codigo as cod_plano',
            'c.conta','m.idinquilino','i.nome as nomeinq','m.id','m.data','m.valor','m.Tipo_d_c','m.historico as hist_mov',
            'm.idproprietario', 'p.nome as nomepro','m.incide_caixa','m.incide_conta_cor','m.idevento','e.nome as nomeevento',
            'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist','m.documento','m.historico','m.tipo_d_c')
            ->whereBetween('m.data', [$dtInicial,$dtFinal])
            ->where('m.tipo_lacto','=','Cheque')
            ->where('m.tipo_d_c','=','Credito')
            ->get();
            $total_credito = $mov_de_contas->sum('valor');

            $mov_de_contas=DB::table('movimentacaos as m')
            ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
            ->leftjoin('inquilino as i', 'm.idinquilino', '=', 'i.idinquilino')
            ->leftjoin('plano_contas as c', 'm.idplano_conta', '=', 'c.id')
            ->leftjoin('evento as e', 'm.idevento', '=', 'e.idevento')
            ->leftjoin('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
            ->select('b.codigo as cod_banco','m.idbanco','b.nome as nomeconta','m.idplano_conta','c.codigo as cod_plano',
            'c.conta','m.idinquilino','i.nome as nomeinq','m.id','m.data','m.valor','m.Tipo_d_c','m.historico as hist_mov',
            'm.idproprietario', 'p.nome as nomepro','m.incide_caixa','m.incide_conta_cor','m.idevento','e.nome as nomeevento',
            'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist','m.documento','m.historico','m.tipo_d_c')
            ->whereBetween('m.data', [$dtInicial,$dtFinal])
            ->where('m.tipo_lacto','=','Cheque')
            ->where('m.tipo_d_c','=','Debito')
            ->get();
            $total_debito = $mov_de_contas->sum('valor');

            $mov_de_contas=DB::table('movimentacaos as m')
            ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
            ->leftjoin('inquilino as i', 'm.idinquilino', '=', 'i.idinquilino')
            ->leftjoin('plano_contas as c', 'm.idplano_conta', '=', 'c.id')
            ->leftjoin('evento as e', 'm.idevento', '=', 'e.idevento')
            ->leftjoin('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
            ->select('b.codigo as cod_banco','m.idbanco','b.nome as nomeconta','m.idplano_conta','c.codigo as cod_plano',
            'c.conta','m.idinquilino','i.nome as nomeinq','m.id','m.data','m.valor','m.Tipo_d_c','m.historico as hist_mov',
            'm.idproprietario', 'p.nome as nomepro','m.incide_caixa','m.incide_conta_cor','m.idevento','e.nome as nomeevento',
            'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist','m.documento','m.historico','m.tipo_d_c')
            ->whereBetween('m.data', [$dtInicial,$dtFinal])
            ->where('m.tipo_lacto','=','Cheque')
            ->get();
    
            $total_relatorio = $mov_de_contas->sum('valor');

            //Agrupamento do Historico Padrão
            $agrupamentos=DB::table('movimentacaos as m')
            ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
            ->leftjoin('inquilino as i', 'm.idinquilino', '=', 'i.idinquilino')
            ->leftjoin('plano_contas as c', 'm.idplano_conta', '=', 'c.id')
            ->leftjoin('evento as e', 'm.idevento', '=', 'e.idevento')
            ->leftjoin('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
            ->select('b.codigo as cod_banco','m.idbanco','b.nome as nomeconta','m.idplano_conta','c.codigo as cod_plano',
            'c.conta','m.idinquilino','i.nome as nomeinq','m.id','m.data','m.valor','m.Tipo_d_c','m.historico as hist_mov',
            'm.idproprietario', 'p.nome as nomepro','m.incide_caixa','m.incide_conta_cor','m.idevento','e.nome as nomeevento',
            'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist','m.documento','m.historico','m.tipo_d_c')
            ->whereBetween('m.data', [$dtInicial,$dtFinal])
            ->where('m.tipo_lacto','=','Cheque')
            ->get();


        } //if($request){

        if ($total_relatorio) {
            $pdf = PDF::loadView('reports/emissao_cheques/pdf_emissao_cheques',compact('empresas','mov_de_contas','total_relatorio','dtInicial','dtFinal','total_debito','total_credito'));
            return $pdf->setPaper('a4')->stream('todos_cheques.pdf');
        }

    }


}
