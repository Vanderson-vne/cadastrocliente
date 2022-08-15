<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Classificacao;
use App\PessoaDupl;
use App\Financeiro;
use App\Imovel;
use App\Mov_Contas;
use App\Movimentacao;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\FinanceiroFormRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PagarController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

        $classificacoes=DB::table('classificacaos as c')
        ->where('c.pag_rec','=','Pagar')
        ->get();

        $fornecedores=DB::table('pessoa_dupls as p')
        ->where('p.for_cli','=','Fornecedor')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->join('proprietario as p', 'im.idproprietario', '=', 'p.idproprietario')
        ->select('im.idimovel','im.endereco','p.nome as nomepro')
        ->where('im.condicao','=','Ativo')
        ->get();

        $dtInicial = $dtInicial = '2001-01-01';
        $dtFinal = $data = date('Y-m-d');

        $pagamentos=DB::table('financeiros as f')
        ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
        ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
        ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
        'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.nome as nomefor')
        ->where('f.pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('f.pagamento')
        ->whereBetween('f.vencimento', [$dtInicial, $dtFinal])
        ->where('pagar_receber','=','Pagar')
        ->orderBy('id','desc')
        ->get();
//dd($pagamentos);
        $contasatrasados = $pagamentos->sum('valor_liquido');

        $dtFinal = $dtFinal = '2099-01-01';
        $dtInicial = $dtInicial = date('Y-m-d');
        $dtInicial= date('Y-m-d', strtotime("+1 days",strtotime($dtInicial)));

        $pagamentos=DB::table('financeiros as f')
        ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
        ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
        ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
        'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.nome as nomefor')
        ->where('f.pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('f.pagamento')
        ->whereBetween('f.vencimento', [$dtInicial, $dtFinal])
        ->where('pagar_receber','=','Pagar')
        ->orderBy('id','desc')
        ->get();

        $contasvencer = $pagamentos->sum('valor_liquido');

        $dtFinal = $dtFinal = date('Y-m-d');
        $dtInicial = $dtInicial = date('Y-m-d');

        $pagamentos=DB::table('financeiros as f')
        ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
        ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
        ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
        'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.nome as nomefor')
        ->where('f.pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('f.pagamento')
        ->whereBetween('f.vencimento', [$dtInicial, $dtFinal])
        ->where('pagar_receber','=','Pagar')
        ->orderBy('id','desc')
        ->get();

        $contasvencerDia = $pagamentos->sum('valor_liquido');

        // $dtFinal = $dtFinal = '2099-01-01';
        // $dtInicial = $dtInicial = date('Y-m-d');
        $dtInicial= date('Y-m-d', strtotime("+1 days",strtotime($dtInicial)));

        $pagamentos=DB::table('financeiros as f')
        ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
        ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
        ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
        'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.nome as nomefor')
        ->where('f.pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('f.pagamento')
        ->whereBetween('f.vencimento', [$dtInicial, $dtInicial])
        ->where('pagar_receber','=','Pagar')
        ->orderBy('id','desc')
        ->get();

        $contasvenceramanha = $pagamentos->sum('valor_liquido');


        $dtFinal = $dtFinal = date('Y-m-d');
        $dtInicial = $dtInicial = date('Y-m-d');

        $pagamentos=DB::table('financeiros as f')
        ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
        ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
        ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
        'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.nome as nomefor')
        //->where('f.pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('f.pagamento')
        ->whereBetween('f.pagamento', [$dtInicial, $dtFinal])
        ->where('pagar_receber','=','Pagar')
        ->orderBy('id','desc')
        ->get();

        $contasPagasDia = $pagamentos->sum('valor_liquido');


        if($request){
    		$query=trim($request->get('searchText'));
    		$pagamentos=DB::table('financeiros as f')
            ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
            ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
            ->leftjoin('imovel as i', 'f.imovel_id', '=', 'i.idimovel')
            ->leftjoin('proprietario as o', 'i.idproprietario', '=', 'o.idproprietario')
            ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
            'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas',
            'p.nome as nomefor','i.endereco as endimovel', 'o.nome as nomepro')
            ->where('pagar_receber','=','Pagar')
            ->where('f.pagamento', '=', null)
            ->orderBy('f.vencimento','asc')
    		->get();

    		$pagamentosbx=DB::table('financeiros as f')
            ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
            ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
            ->leftjoin('imovel as i', 'f.imovel_id', '=', 'i.idimovel')
            ->leftjoin('proprietario as o', 'i.idproprietario', '=', 'o.idproprietario')
            ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
            'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas',
            'p.nome as nomefor','i.endereco as endimovel', 'o.nome as nomepro')
            ->where('pagar_receber','=','Pagar')
            ->where('f.pagamento', '!=', null)
            ->orderBy('f.pagamento','asc')
    		->get();

            //dd($pagamentos);

            return view('financeiro.pagar.index', [
                "pagamentos"=>$pagamentos, 
                "pagamentosbx"=>$pagamentosbx, 
                "empresas"=>$empresas,
                "classificacoes"=>$classificacoes,
                "fornecedores"=>$fornecedores,
                "contasatrasados"=>$contasatrasados,
                "contasvencer"=>$contasvencer,
                "contasvencerDia"=>$contasvencerDia,
                "contasvenceramanha"=>$contasvenceramanha,
                "contasPagasDia"=>$contasPagasDia,
                "searchText"=>$query
    			]);
    	}
    }

    public function recibosAll(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

        $classificacoes=DB::table('classificacaos as c')
        ->where('c.pag_rec','=','Pagar')
        ->get();

        $fornecedores=DB::table('pessoa_dupls as p')
        ->where('p.for_cli','=','Fornecedor')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->join('proprietario as p', 'im.idproprietario', '=', 'p.idproprietario')
        ->select('im.idimovel','im.endereco','p.nome as nomepro')
        ->where('im.condicao','=','Ativo')
        ->get();

        $dtInicial = $dtInicial = '2001-01-01';
        $dtFinal = $data = date('Y-m-d');

        $pagamentos=DB::table('financeiros as f')
        ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
        ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
        ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
        'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.nome as nomefor')
        ->where('f.pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('f.pagamento')
        ->whereBetween('f.vencimento', [$dtInicial, $dtFinal])
        ->where('pagar_receber','=','Pagar')
        ->orderBy('id','desc')
        ->get();

        $contasatrasados = $pagamentos->sum('valor_liquido');

        $dtFinal = $dtFinal = '2099-01-01';
        $dtInicial = $dtInicial = date('Y-m-d');
        $dtInicial= date('Y-m-d', strtotime("+1 days",strtotime($dtInicial)));

        $pagamentos=DB::table('financeiros as f')
        ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
        ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
        ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
        'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.nome as nomefor')
        ->where('f.pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('f.pagamento')
        ->whereBetween('f.vencimento', [$dtInicial, $dtFinal])
        ->where('pagar_receber','=','Pagar')
        ->orderBy('id','desc')
        ->get();

        $contasvencer = $pagamentos->sum('valor_liquido');

        $dtFinal = $dtFinal = date('Y-m-d');
        $dtInicial = $dtInicial = date('Y-m-d');

        $pagamentos=DB::table('financeiros as f')
        ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
        ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
        ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
        'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.nome as nomefor')
        ->where('f.pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('f.pagamento')
        ->whereBetween('f.vencimento', [$dtInicial, $dtFinal])
        ->where('pagar_receber','=','Pagar')
        ->orderBy('id','desc')
        ->get();

        $contasvencerDia = $pagamentos->sum('valor_liquido');

        $dtInicial= date('Y-m-d', strtotime("+1 days",strtotime($dtInicial)));

        $pagamentos=DB::table('financeiros as f')
        ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
        ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
        ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
        'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.nome as nomefor')
        ->where('f.pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('f.pagamento')
        ->whereBetween('f.vencimento', [$dtInicial, $dtInicial])
        ->where('pagar_receber','=','Pagar')
        ->orderBy('id','desc')
        ->get();

        $contasvenceramanha = $pagamentos->sum('valor_liquido');

        $dtFinal = $dtFinal = date('Y-m-d');
        $dtInicial = $dtInicial = date('Y-m-d');

        $pagamentos=DB::table('financeiros as f')
        ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
        ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
        ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
        'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.nome as nomefor')
        //->where('f.pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('f.pagamento')
        ->whereBetween('f.pagamento', [$dtInicial, $dtFinal])
        ->where('pagar_receber','=','Pagar')
        ->orderBy('id','desc')
        ->get();

        $contasPagasDia = $pagamentos->sum('valor_liquido');


        if($request){
    		$query=trim($request->get('searchText'));
    		$pagamentos=DB::table('financeiros as f')
            ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
            ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
            ->leftjoin('imovel as i', 'f.imovel_id', '=', 'i.idimovel')
            ->leftjoin('proprietario as o', 'i.idproprietario', '=', 'o.idproprietario')
            ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
            'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas',
            'p.nome as nomefor','i.endereco as endimovel', 'o.nome as nomepro')
            ->where('pagar_receber','=','Pagar')
            ->where('f.pagamento', '!=', null)
            ->orderBy('id','desc')
    		->get();

            //dd($pagamentos);

            return view('financeiro.pagar.index', [
                "pagamentos"=>$pagamentos, 
                "empresas"=>$empresas,
                "classificacoes"=>$classificacoes,
                "fornecedores"=>$fornecedores,
                "contasatrasados"=>$contasatrasados,
                "contasvencer"=>$contasvencer,
                "contasvencerDia"=>$contasvencerDia,
                "contasvenceramanha"=>$contasvenceramanha,
                "contasPagasDia"=>$contasPagasDia,
                "searchText"=>$query
    			]);
    	}
    }

    public function create(){

        $classificacoes=DB::table('classificacaos as c')
        ->where('c.pag_rec','=','Pagar')
        ->get();

        $fornecedores=DB::table('pessoa_dupls as p')
        ->where('p.for_cli','=','Fornecedor')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->join('proprietario as p', 'im.idproprietario', '=', 'p.idproprietario')
        ->select('im.idimovel','im.endereco','p.nome as nomepro')
        ->where('im.condicao','=','Ativo')
        ->get();


        return view("financeiro.pagar.create",[
            "classificacoes"=>$classificacoes,
            "fornecedores"=>$fornecedores,
            "imoveis"=>$imoveis,
        ]);
    }
 
    public function store(FinanceiroFormRequest $request){
       
        if($request->duplicata == null){
            $this->validate($request,[
                'duplicata'=>'required',
            ]);
        }

        $imovel=$request->get('imovel_id');
        if($imovel == "Selecione o Imovel ..."){
            $imovel=NULL;
        }


        $pagar = new financeiro;
        $pagar->pessoa_dupls_id=$request->get('pessoa_dupls_id');
        $pagar->pessoa_vend_id='1';
        $pagar->classificacao_id=$request->get('classificacao_id');
        $pagar->imovel_id=$imovel;
        $pagar->duplicata=$request->get('duplicata');
        $pagar->contabil=$request->get('contabil');
        $pagar->tipo=$request->get('tipo');
        $pagar->nf=$request->get('nf');
        $pagar->valor=$request->get('valor');
        $pagar->juros=$request->get('juros');
        $pagar->juros_dia=$request->get('juros_dia');
        $pagar->desconto=$request->get('desconto');
        $pagar->vencimento=$request->get('vencimento');
        $pagar->historico=$request->get('historico');
        $pagar->valor_liquido= ($pagar->valor + $pagar->juros - $pagar->desconto);
        $pagar->pagar_receber='Pagar';
        $pagar->save();

        return Redirect::to('financeiro/pagar');
    }


    public function show($id){
    	return view("financeiro/pagar.show",
    		["classificacao"=>classificacao::findOrFail($id)]);
    }

    public function edit($id){

        $classificacoes=DB::table('classificacaos as c')
        ->where('c.pag_rec','=','Pagar')
        ->get();

        $fornecedores=DB::table('pessoa_dupls as p')
        ->where('p.for_cli','=','Fornecedor')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->join('proprietario as p', 'im.idproprietario', '=', 'p.idproprietario')
        ->select('im.idimovel','im.endereco','p.nome as nomepro')
        ->where('im.condicao','=','Ativo')
        ->get();

        $bancos=DB::table('banco as ban')
        ->get();

        $transacoes=DB::table('transacao as tra')
        ->where('tra.tipo','=','Debito')
        ->get();

        $pagamentos=DB::table('financeiros as f')
        ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
        ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
        ->leftjoin('imovel as i', 'f.imovel_id', '=', 'i.idimovel')
        ->select('f.id','f.duplicata','f.nf','f.tipo','valor','valor_liquido',
        'f.juros','f.desconto','f.pagamento','f.vencimento','c.id as idclas',
        'c.nome as nomeclas','p.id as idfor','p.nome as nomefor','f.imovel_id','i.endereco as endimovel')
        ->where('pagar_receber','=','Pagar')
        ->orderBy('id','desc')
        ->get();

    	return view("financeiro/pagar.edit",
    		["financeiro"=>financeiro::findOrFail($id),
            "classificacoes"=>$classificacoes,
            "fornecedores"=>$fornecedores,
            "pagamentos"=>$pagamentos,
            "imoveis"=>$imoveis,
			"transacoes"=>$transacoes,
			"bancos"=>$bancos,
        ]);
    }            


    public function update(FinanceiroFormRequest $request, $id){
        $empresas=DB::table('empresa as emp')->get();
		$idempresa=$empresas[0]->idempresa;        

        $idImovel=$request->get('imovel_id');

        $imoveis = DB::table('imovel as im')
        ->where('im.idimovel', '=', $idImovel)
        ->get();

        $valor=$request->get('valor');
        $juros=$request->get('juros');
        $juros_dia=$request->get('juros_dia');
        $desconto=$request->get('desconto');
        $pgto_conta=$request->get('pgto_conta');

        $liquido=0;
        $liquido=($valor+$juros-$desconto-$pgto_conta);

        $pagar=financeiro::findOrFail($id);
        $pagamento=$pagar->pagamento;
        $pagamentoDigitado=$request->get('pagamento');

        $pagar->pessoa_dupls_id=$request->get('pessoa_dupls_id');
        $pagar->pessoa_vend_id='1';
        $pagar->classificacao_id=$request->get('classificacao_id');
        $pagar->imovel_id=$request->get('imovel_id');
        $pagar->duplicata=$request->get('duplicata');
        $pagar->contabil=$request->get('contabil');
        $pagar->tipo=$request->get('tipo');
        $pagar->nf=$request->get('nf');
        $pagar->valor=$request->get('valor');
        $pagar->juros=$request->get('juros');
        $pagar->juros_dia=$request->get('juros_dia');
        $pagar->desconto=$request->get('desconto');
        $pagar->vencimento=$this->parseDate($request->get('vencimento')) . ' ' . Carbon::now()->toTimeString(); //$request->get('vencimento');
        if ($pagamentoDigitado != null) $pagar->pagamento=$this->parseDate($request->get('pagamento')) . ' ' . Carbon::now()->toTimeString(); //$request->get('pagamento');
        $pagar->nro_cheque=$request->get('nro_cheque');
        $pagar->pgto_conta=$request->get('pgto_conta');
        $pagar->historico=$request->get('historico');
        $pagar->banco=$request->get('idbanco');
        $pagar->conta=$request->get('idtransacao');
        $pagar->valor_liquido= $liquido;
        $pagar->pagar_receber='Pagar';
    	$pagar->update();

        if ($pagamento == null) {
            //Movimentação Mov_contas
                $bb=$request->get('idbanco');
                $bancos=DB::table('banco as ban')
                ->where('ban.idbanco','=', $bb)
                ->get();

                $trans=$request->get('idtransacao');
                $transacoes=DB::table('transacao as tra')
                ->where('tra.idtransacao','=', $trans)
                ->get();

                $valor=$liquido;
                $comp="Nao";

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
                $mov_contas->idhistorico='1';
                $mov_contas->data=$this->parseDate($request->get('pagamento')) . ' ' . Carbon::now()->toTimeString();
                $mov_contas->documento=$request->get('duplicata');
                $mov_contas->valor=$liquido;
                $mov_contas->historico=$request->get('historico');
                $mov_contas->compensado=$comp; 
                $mov_contas->parcial=$total_saldo;  
                $mov_contas->save();

                $banco_up = DB::table('banco')
                ->where('idbanco', $bb)
                ->update([
                'saldo' => $total_saldo,
                    ]);	

                $locacao=$imoveis[0]->idlocacao;

                if ($idImovel) {
                //////Movimentações 
                $movimentacao = new Movimentacao;
                $movimentacao->idempresa = $empresas[0]->idempresa;
                if ($locacao != 0) {
                    $movimentacao->idinquilino = $imoveis[0]->idinquilino;
                    $movimentacao->idlocacao = $imoveis[0]->idlocacao;
                }
                $movimentacao->idproprietario = $imoveis[0]->idproprietario;
                //$movimentacao->idrecibo = $id;
                //$movimentacao->idevento = $det->idevento;
                $movimentacao->incide_conta_cor = 'Sim';
                $movimentacao->Tipo_D_C = 'Debito';
                //$movimentacao->complemento = $det->complemento;
                $movimentacao->data =$this->parseDate($request->get('pagamento')) . ' ' . Carbon::now()->toTimeString(); //$request->get('pagamento') . ' ' . Carbon::now()->toTimeString();
                $movimentacao->documento = $request->get('duplicata');
                //$movimentacao->mes_ano = $recibo->mes_ano;
                $movimentacao->valor = $liquido;
                $movimentacao->historico = $request->get('historico');
                $movimentacao->incide_caixa = 'Sim';
                $movimentacao->caixa_rec_pag = 'Duplicata';
                $movimentacao->tipo_lacto = 'Pagto';
                $movimentacao->idbanco = $request->get('idbanco');
                $movimentacao->save();
            }


        }
    	return Redirect::to('financeiro/pagar');
    }


    public function destroy($id){
    	$financeiro=financeiro::findOrFail($id);
    	$financeiro->delete();
    	return Redirect::to('financeiro/pagar');
    }

    private function parseDate($date, $plusDay = false)
    {
        if ($plusDay == false)
            return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
        else
            return date('Y-m-d', strtotime("+1 day", strtotime(str_replace("/", "-", $date))));
    }

}
