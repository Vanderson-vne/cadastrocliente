<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Movimentacao;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\MovimentacaoFormRequest;
use App\Inquilino;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;


class MovimentacaoController extends Controller
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

        $historicos=DB::table('historico_padraos as his')
        ->get();

        $dtnow=$dtnow=date('Y-m-d');

        $results = DB::select('SELECT idevento, SUM(valor) AS "valor"
        FROM movimentacaos
        GROUP BY idevento');

        // $movimentacoes=DB::table('movimentacaos as m')
        // ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
        // ->leftjoin('inquilino as i', 'm.idinquilino', '=', 'i.idinquilino')
        // ->leftjoin('banco as b', 'm.idbanco', '=', 'b.idbanco')
        // ->leftjoin('evento as e', 'm.idevento', '=', 'e.idevento')
        // ->leftjoin('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
        // ->select('m.idrecibo','m.idinquilino','m.id','m.data','m.valor','m.Tipo_d_c','m.idinquilino','i.nome as nomeinq',
        // 'm.historico','m.idproprietario', 'p.nome as nomepro','m.tipo_lacto',
        // 'b.nome as nomeconta','m.incide_caixa','m.incide_conta_cor','e.nome as nomeevento',
        // 'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist')
        // ->where('m.tipo_lacto','!=','Cheque')
        // ->groupBy('m.idevento')
        // ->get();

        // $users = DB::table('movimentacaos as m')
        // ->groupBy('m.idevento')
        // ->having('m.id', '>', 1)
        // ->get();

        // $users = DB::table('movimentos as m')->select('m.ideventos')->groupBy('m.ideventos')
        //          ->get();
        //          dd($users);

        if($request){
            $query=trim($request->get('searchText'));
            $movimentacoes=DB::table('movimentacaos as m')
            ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
            ->leftjoin('inquilino as i', 'm.idinquilino', '=', 'i.idinquilino')
            ->leftjoin('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->leftjoin('evento as e', 'm.idevento', '=', 'e.idevento')
            ->leftjoin('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
            ->select('m.idrecibo','m.idinquilino','m.id','m.data','m.valor','m.Tipo_d_c','m.idinquilino','i.nome as nomeinq',
            'm.historico','m.idproprietario', 'p.nome as nomepro','m.tipo_lacto',
            'b.nome as nomeconta','m.incide_caixa','m.incide_conta_cor','e.nome as nomeevento',
            'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist')
            ->where('m.data','LIKE', '%'.$query.'%')
            ->where('m.tipo_lacto','!=','Cheque')
            //->orderBy('m.id','desc')
            ->get();

            return view('financeiro.movimentacao.index', [
                "movimentacao"=>$movimentacoes,
                "empresas"=>$empresas,
                "results"=>$results,
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

        $transacoes=DB::table('transacao as tra')
		//->where('tra.idtransacao','=', $mov_de_contas[0]->idtransacao)
        ->get();

        $plano_contas=DB::table('plano_contas as pla')
        ->orderBy('pla.codigo')
        ->get();

        $historicos=DB::table('historico_padraos as his')
        ->get();

        return view("financeiro.movimentacao.create",[
            "empresas"=>$empresas,
            "proprietarios"=>$proprietarios,
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


        $movimentacao = new movimentacao;
        $movimentacao->idempresa=$idempresa;
        $movimentacao->idinquilino=$r_inq;
        $movimentacao->idproprietario=$r_prop;
        $movimentacao->idbanco=$request->get('idbanco');
        $movimentacao->idmov_contas=$request->get('idmov_contas');
        $movimentacao->idplano_conta=$r_plano_Contas;
        $movimentacao->idevento=$r_eventos;
        $movimentacao->idhistorico=$request->get('idhistorico');
        $movimentacao->conta=$request->get('conta');
        //$movimentacao->data= $request->get('data').' '.$hora_atual;
        $movimentacao->data= $request->get('data').' '.Carbon::now()->toTimeString();
        $movimentacao->mes_ano=$request->get('mes_ano');
        $movimentacao->historico=$request->get('historico');
        $movimentacao->documento=$request->get('documento');
        $movimentacao->valor=$request->get('valor');
        $movimentacao->complemento=$request->get('complemento');
        $movimentacao->comissao=$request->get('comissao');
        $movimentacao->incide_caixa=$request->get('incide_caixa');
        $movimentacao->caixa_rec_pag=$request->get('caixa_rec_pag');
        $movimentacao->incide_conta_cor=$request->get('incide_conta_cor');
        $movimentacao->Tipo_d_c=$request->get('Tipo_d_c');
        $movimentacao->ult_extrato=$request->get('ult_extrato');
        $movimentacao->tipo_lacto=$request->get('tipo_lacto');
        $movimentacao->save();
        return Redirect::to('financeiro/movimentacao');
    }


    public function show($id){
    	return view("financeiro/movimentacao.show",
    		["movimentacao"=>movimentacao::findOrFail($id)]);
    }

    public function edit($id){

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
        ->where('i.condicao','=','Ativo')
        ->get();

        $bancos=DB::table('banco as ban')
        ->get();

        $eventos=DB::table('evento as eve')
        ->get();

        $transacoes=DB::table('transacao as tra')
		//->where('tra.idtransacao','=', $mov_de_contas[0]->idtransacao)
        ->get();

        $plano_contas=DB::table('plano_contas as pla')
        ->get();

        $historicos=DB::table('historico_padraos as his')
        ->get();

                    // ->join('proprietario as p', function ($join) {
                    //     $join->on('m.idproprietario', '=', 'p.idproprietario')
                    //          ->where('p.idproprietario', '!=', null);
                    // })

        $movimentacoes=DB::table('movimentacaos as m')
        ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
        ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
        ->leftjoin('inquilino as i', 'm.idinquilino', '=', 'i.idinquilino')
        ->leftjoin('plano_contas as c', 'm.idplano_conta', '=', 'c.id')
        ->leftjoin('evento as e', 'm.idevento', '=', 'e.idevento')
        ->leftjoin('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
        ->select('b.codigo as cod_banco','m.idbanco','b.nome as nomeconta','m.idplano_conta','c.codigo as cod_plano',
        'c.conta','m.idinquilino','i.nome as nomeinq','m.id','m.data','m.valor','m.Tipo_d_c','m.historico as hist_mov',
        'm.idproprietario', 'p.nome as nomepro','m.incide_caixa','m.incide_conta_cor','m.idevento','e.nome as nomeevento',
        'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist')
        ->where('m.tipo_lacto','!=','Cheque')
        ->where('m.id','=', $id)
        ->get();


    	return view("financeiro/movimentacao.edit",
            ["movimentacao"=>movimentacao::findOrFail($id),
            "empresas"=>$empresas,
            "proprietarios"=>$proprietarios,
			"transacoes"=>$transacoes,
			"plano_contas"=>$plano_contas,
			"bancos"=>$bancos,
            "eventos"=>$eventos,
            "historicos"=>$historicos,
            "inquilinos"=>$inquilinos,
            "movimentacoes"=>$movimentacoes
            ]);
    }


    public function update(MovimentacaoFormRequest $request, $id){

        $empresas=DB::table('empresa as emp')->get();
		$idempresa=$empresas[0]->idempresa;

        //dd($request);

        $movimentacao=movimentacao::findOrFail($id);
        $movimentacao->idempresa=$idempresa;
        $movimentacao->idinquilino=$request->get('idinquilino'); //$r_inq;
        $movimentacao->idproprietario=$request->get('idproprietario'); //$r_prop;
        $movimentacao->idbanco=$request->get('idbanco');
        $movimentacao->idmov_contas=$request->get('idmov_contas');
        $movimentacao->idplano_conta=$request->get('idplano_conta'); //$r_plano_Contas;
        $movimentacao->idevento=$request->get('idevento'); //$r_eventos;
        $movimentacao->idhistorico=$request->get('idhistorico');
        $movimentacao->conta=$request->get('conta');
        $movimentacao->data=$request->get('data').' '.Carbon::now()->toTimeString();
        $movimentacao->mes_ano=$request->get('mes_ano');
        $movimentacao->historico=$request->get('historico');
        $movimentacao->documento=$request->get('documento');
        $movimentacao->valor=$request->get('valor');
        $movimentacao->complemento=$request->get('complemento');
        $movimentacao->comissao=$request->get('comissao');
        $movimentacao->incide_caixa=$request->get('incide_caixa');
        $movimentacao->caixa_rec_pag=$request->get('caixa_rec_pag');
        $movimentacao->incide_conta_cor=$request->get('incide_conta_cor');
        $movimentacao->Tipo_d_c=$request->get('Tipo_d_c');
        $movimentacao->ult_extrato=$request->get('ult_extrato');
        $movimentacao->tipo_lacto=$request->get('tipo_lacto');

    	$movimentacao->update();
    	return Redirect::to('financeiro/movimentacao');
    }


    public function destroy($id){
    	$movimentacao=movimentacao::findOrFail($id);
    	$movimentacao->delete();
    	return Redirect::to('financeiro/movimentacao');
    }
}
