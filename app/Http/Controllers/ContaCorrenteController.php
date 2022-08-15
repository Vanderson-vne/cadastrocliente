<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Movimentacao;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\MovimentacaoFormRequest;
use App\Inquilino;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use PDF;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class ContaCorrenteController extends Controller
{
    public function __Construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){


        
        $user = Auth::user();

        if($user->hasAnyRole('Admin','Gerente','Caixa')){    
                $empresas=DB::table('empresa as emp')
                ->get();
                
                $proprietarios=DB::table('proprietario as p')
                ->where('p.condicao','=','Ativo')
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
                    return view('reports.conta_corrente.index', [
                        "movimentacoes"=>$movimentacoes, 
                        "empresas"=>$empresas,
                        "proprietarios"=>$proprietarios,
                        "searchText"=>$query
                    ]);

                }
        }

        if($user->hasAnyRole('Proprietario')){    
            $empresas=DB::table('empresa as emp')
            ->get();
            
            $userid=$user->id;

            $proprietarios=DB::table('proprietario as p')
            ->where('p.condicao','=','Ativo')
            ->where('p.user_id','=',$userid)
            ->get();

            $idproprietario=$proprietarios[0]->idproprietario;

            if($request){
                $query=trim($request->get('searchText'));
                $movimentacoes=DB::table('movimentacaos as m')
                ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
                ->leftjoin('banco as b', 'm.idbanco', '=', 'b.idbanco')
                ->join('transacao as t', 'm.idtransacao', '=', 't.idtransacao')
                ->select('m.id','m.data','m.valor','m.Tipo_d_c','m.historico','m.predatado','m.compensado','m.documento','m.nominal','m.idproprietario', 'p.nome as nomepro',
                'b.nome as nomeconta','b.idbanco','m.incide_caixa','m.incide_conta_cor','t.transacao','t.tipo')
                ->where('m.idproprietario','=', $idproprietario)
                ->where('m.data','LIKE', '%'.$query.'%')
                ->where('m.tipo_lacto','=','Cheque')
                ->orderBy('m.id','desc')
                ->get();
                return view('reports.conta_corrente.index', [
                    "movimentacoes"=>$movimentacoes, 
                    "empresas"=>$empresas,
                    "proprietarios"=>$proprietarios,
                    "searchText"=>$query
                ]);

            }
    }

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


    public function pdfContaCorrente(Request $request){
    
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
             $r_idproprietario=trim($request->get('idproprietario'));
             
             $proprietarios=DB::table('proprietario as p')
             ->where('p.idproprietario','=', $r_idproprietario)
             ->get();

             $saldo=1;
             if ($dtInicial=="") {
                $saldo=0;
                $dtInicial='2001-01-01';
            }
            if ($dtFinal=="") {
                $dtFinal='4001-01-01';
            }

            //Controle de Saldo 
            $mov_de_contas=DB::table('movimentacaos as m')
            ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
            ->leftjoin('inquilino as i', 'm.idinquilino', '=', 'i.idinquilino')
            ->leftjoin('plano_contas as c', 'm.idplano_conta', '=', 'c.id')
            ->leftjoin('evento as e', 'm.idevento', '=', 'e.idevento')
            ->leftjoin('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
            ->select('b.codigo as cod_banco','m.idbanco','b.nome as nomeconta','m.idplano_conta','c.codigo as cod_plano',
            'c.conta','m.idinquilino','i.nome as nomeinq','m.id','m.data','m.valor','m.parcial','m.Tipo_d_c','m.historico as hist_mov',
            'm.idproprietario', 'p.nome as nomepro','m.incide_caixa','m.incide_conta_cor','m.idevento','e.nome as nomeevento',
            'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist','m.documento','m.historico','m.tipo_d_c')
            ->whereBetween('m.data', [$dtInicial,$dtFinal])
            ->where('m.idproprietario','=', $r_idproprietario)
            ->where('m.incide_conta_cor','=', 'Sim')
            ->orderBy('m.data','asc')
            ->get();

            // foreach ($mov_de_contas as $det) {
            //     $idmov=$det->id;
            //     $detalhe=Movimentacao::findOrFail($idmov);
            //     $detalhe->parcial=0;
            //     $detalhe->update();
            // }

            //dd($mov_de_contas);

            $idmov=$mov_de_contas[0]->id;
            $detalhe=Movimentacao::findOrFail($idmov);
            $tipo=$detalhe->Tipo_d_c;
            if ($saldo == 0) {
                $detalhe->parcial=0;
                $detalhe->update();
            }
            if ($saldo == 1) {
                if ($Tipo = 'Credito') {
                    $saldo=$detalhe->parcial+$detalhe->valor;
                }
                if ($Tipo = 'Debito') {
                    $saldo=$detalhe->parcial-$detalhe->valor;
                }
            }

         echo $idmov.' ID<br>';
         echo $tipo.' Tipo_d_c<br>';
         echo $saldo.' Saldo<br>';
         echo $detalhe->parcial.' parcial<br>';
         echo $detalhe->valor.' valor<br>';
         echo '============================================<br>';    

            foreach ($mov_de_contas as $det) {
                $idmov=$det->id;
                $detalhe=Movimentacao::findOrFail($idmov);
                $tipo=$det->Tipo_d_c;

         echo $saldo.' Antes Saldo<br>';

                if ($tipo == 'Credito') {
                    if ($saldo != 0) {
                        $saldo=$saldo+$detalhe->valor;
                        $detalhe->parcial=$saldo;
                    }
                    if ($saldo == 0) {
                        $saldo=$saldo+$detalhe->valor;
                        $detalhe->parcial=$saldo;
                    }
        echo $saldo.' Saldo apos Credito<br>';
                }
                if ($tipo ==  'Debito') {
                    if ($saldo != 1) {
                        $vlrDebito=$detalhe->valor;
                        $saldo=$saldo-$vlrDebito;
                        $detalhe->parcial=$saldo;
                        if ($saldo == 0) {
                            $saldo='1';
                        }    
                    }
                    if ($saldo == 0) {
                        $saldo=$saldo-$detalhe->valor;
                        $detalhe->parcial=$saldo;
                    }
        //  echo $saldo.' Saldo apos Debito'.$vlrDebito.'<br>';
        }

          echo $idmov.' ID<br>';
          echo $tipo.' Tipo_d_c<br>';
          echo $saldo.' Saldo<br>';
          echo $detalhe->parcial.' parcial<br>';
          echo $detalhe->valor.' valor<br>';
          echo '============================================<br>';    

                $detalhe->update();
            }

           // dd($mov_de_contas);

            $mov_de_contas=DB::table('movimentacaos as m')
            ->join('banco as b', 'm.idbanco', '=', 'b.idbanco')
            ->leftjoin('proprietario as p', 'm.idproprietario', '=', 'p.idproprietario')
            ->leftjoin('inquilino as i', 'm.idinquilino', '=', 'i.idinquilino')
            ->leftjoin('plano_contas as c', 'm.idplano_conta', '=', 'c.id')
            ->leftjoin('evento as e', 'm.idevento', '=', 'e.idevento')
            ->leftjoin('historico_padraos as h', 'm.idhistorico', '=', 'h.id')
            ->select('b.codigo as cod_banco','m.idbanco','b.nome as nomeconta','m.idplano_conta','c.codigo as cod_plano',
            'c.conta','m.idinquilino','i.nome as nomeinq','m.id','m.data','m.valor','m.parcial','m.Tipo_d_c','m.historico as hist_mov',
            'm.idproprietario', 'p.nome as nomepro','m.incide_caixa','m.incide_conta_cor','m.idevento','e.nome as nomeevento',
            'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist','m.documento','m.historico','m.tipo_d_c')
            ->where('m.idproprietario','!=',Null)
            ->whereBetween('m.data', [$dtInicial,$dtFinal])
            ->where('m.idproprietario','=', $r_idproprietario)
            ->where('m.incide_conta_cor','=', 'Sim')
            ->where('m.tipo_d_c','=', 'Credito')
            ->orderBy('m.data','asc')
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
            'c.conta','m.idinquilino','i.nome as nomeinq','m.id','m.data','m.valor','m.parcial','m.Tipo_d_c','m.historico as hist_mov',
            'm.idproprietario', 'p.nome as nomepro','m.incide_caixa','m.incide_conta_cor','m.idevento','e.nome as nomeevento',
            'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist','m.documento','m.historico','m.tipo_d_c')
            ->where('m.idproprietario','!=',Null)
            ->whereBetween('m.data', [$dtInicial,$dtFinal])
            ->where('m.idproprietario','=', $r_idproprietario)
            ->where('m.incide_conta_cor','=', 'Sim')
            ->where('m.tipo_d_c','=', 'Debito')
            ->orderBy('m.data','asc')
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
            'c.conta','m.idinquilino','i.nome as nomeinq','m.id','m.data','m.valor','m.parcial','m.Tipo_d_c','m.historico as hist_mov',
            'm.idproprietario', 'p.nome as nomepro','m.incide_caixa','m.incide_conta_cor','m.idevento','e.nome as nomeevento',
            'm.idhistorico','h.codigo as cod_hist','h.historico as desc_hist','m.documento','m.historico','m.tipo_d_c')
            ->where('m.idproprietario','!=',Null)
            ->whereBetween('m.data', [$dtInicial,$dtFinal])
            ->where('m.idproprietario','=', $r_idproprietario)
            ->where('m.incide_conta_cor','=', 'Sim')
            ->orderBy('m.data','asc')
            ->get();

            if ($mov_de_contas->isEmpty()){
                $codigoroprietario=$proprietarios[0]->idproprietario;
                $nomeproprietario=$proprietarios[0]->nome;
                //dd('Not');
            } else {
                $codigoroprietario=$mov_de_contas[0]->idproprietario;
                $nomeproprietario=$mov_de_contas[0]->nomepro;
                 //dd('Yes');
            }

           
            //dd($mov_de_contas);

            $total_relatorio = $mov_de_contas->sum('valor');

         } //if($request){

         $pdf = PDF::loadView('reports/conta_corrente/pdf_conta_corrente',compact('empresas','mov_de_contas',
         'total_relatorio','dtInicial','dtFinal','nomeproprietario','codigoroprietario','total_credito','total_debito'));
         return $pdf->setPaper('a4')->stream('todos_conta_corrente.pdf');

    }
}
