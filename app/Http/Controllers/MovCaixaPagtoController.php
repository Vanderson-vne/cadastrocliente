<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Empresa;
use App\Movimentacao;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\MovimentacaoFormRequest;
use App\Inquilino;
use App\Evento;
use Illuminate\Support\Facades\DB;

use PDF;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class MovCaixaPagtoController extends Controller
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

        $historicos=DB::table('historico_padraos as his')
        ->get();

    	if($request){
    		$query=trim($request->get('searchText'));
            $movimentacoes=DB::table('movimentacaos as m')
            ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
            ->leftjoin('inquilino as i', 'm.idinquilino', '=', 'i.idinquilino')
            ->leftjoin('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->leftjoin('evento as e', 'm.idevento', '=', 'e.idevento')
            ->leftjoin('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
            ->select('m.idrecibo','m.idinquilino','m.id','m.data','m.valor','m.Tipo_d_c','m.idinquilino','i.nome as nomeinq',
            'm.historico','m.idproprietario', 'p.nome as nomepro',
            'b.nome as nomeconta','m.incide_caixa','m.incide_conta_cor','e.nome as nomeevento',
            'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist')
            ->where('m.data','LIKE', '%'.$query.'%')
            ->where('m.tipo_lacto','!=','Cheque')
    		->orderBy('m.id','desc')
    		->get();
    		return view('reports.mov_caixa_pgto.index', [
                "movimentacao"=>$movimentacoes, 
                "empresas"=>$empresas,
                "bancos"=>$bancos,
                "searchText"=>$query
    		]);

    	}
    }

    public function pdfMovCaixaPgto(Request $request){
    
        //dd($request);
        $data = Carbon::create('');

        $empresas=DB::table('empresa as emp')
        ->get();

        $bancos=DB::table('banco as ban')
		->get();
		
		$historicos=DB::table('historico_padraos as his')
        ->get();

        $eventos = Evento::all();
        $grupos = Evento::select('agrupamento')->groupBy('agrupamento')->get();
         
        //dd($grupos);

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
            'm.idproprietario', 'p.nome as nomepro','m.incide_caixa','m.incide_conta_cor','m.idevento','e.nome as nomeevento','e.tipo','e.agrupamento',
            'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist','m.documento','m.historico','m.tipo_d_c')
            ->whereBetween('m.data', [$dtInicial,$dtFinal])
            ->where('m.idbanco','=',$idbanco)
            ->where('m.tipo_lacto','=','Pagto')
            ->where('m.incide_caixa','=','Sim')
            ->get();
    
            $total_relatorio = $mov_de_contas->sum('valor');
            $total_credito= 0;
            $total_deito= 0;

           
        } //if($request){
            
            //dd($mov_de_contas[0]->eventos);

        if ($total_relatorio) {
            $pdf = PDF::loadView('reports/mov_caixa_pgto/pdf_movcaixa',compact('empresas','mov_de_contas','total_relatorio','dtInicial','dtFinal','eventos','grupos'));
            return $pdf->setPaper('a4')->stream('todos_mov_caixa.pdf');
        }

    }



}
