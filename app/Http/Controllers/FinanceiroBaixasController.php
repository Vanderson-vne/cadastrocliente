<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Empresa;
use App\Classificacao;
use App\PessoaDupl;
use App\Financeiro;
use App\User;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\FinanceiroFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use PDF;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class FinanceiroBaixasController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){
    
        $user = Auth::user();

        if($user->hasAnyRole('Admin','Gerente','Caixa')){        
                $empresas=DB::table('empresa as emp')
                ->get();

                $classificacoes=DB::table('classificacaos as c')
                ->where('c.pag_rec','=','Pagar')
                ->get();

                $fornecedores=DB::table('pessoa_dupls as p')
                ->where('p.for_cli','=','Fornecedor')
                ->where('p.condicao','=','Ativo')
                ->get();

               
                if($request){
                    $query=trim($request->get('searchText'));
                    $pagamentos=DB::table('financeiros as f')
                    ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
                    ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
                    ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
                    'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.nome as nomefor')
                    ->where('pagar_receber','=','Pagar')
                    ->where('f.pagamento', '=', null)
                    ->orderBy('id','desc')
                    ->get();

                    return view('reports.duplPagarBaixa.index', [
                        "pagamentos"=>$pagamentos,
                        "fornecedores"=>$fornecedores,
                        "empresas"=>$empresas,
                        "searchText"=>$query
                        ]);
                }
        }

    }


     public function pdfFinBaixas(Request $request){
        
        $empresas=DB::table('empresa as e')->get();

        $mesano=trim($request->get('mesano'));
        
        $dtInicial=trim($request->get('dtVectoInicial'));
        $dtFinal=trim($request->get('dtVectoFinal'));

        $idfornecedor_filtro=trim($request->get('idfornecedor'));
    
        $dados=$request->get('idfornecedor');
        $array = explode('_', $dados);
        $idTodosFornecedores=$array[0];

        $saldo=1;
        if ($dtInicial=="") {
           $saldo=0;
           $dtInicial='2001-01-01';
       }
       if ($dtFinal=="") {
           $dtFinal='4001-01-01';
       }


    //    $fornecedores=DB::table('pessoa_dupls as p')
    //    ->where('p.id', 'LIKE', $idfornecedor_filtro)
    //    ->get();

       $fornecedores=DB::table('pessoa_dupls as p')
       ->where('p.for_cli','=','Fornecedor')
       ->where('p.condicao','=','Ativo')
       ->get();

         if ($idTodosFornecedores!='Todos') {

                $pagamentos=DB::table('financeiros as f')
                ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
                ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
                ->select('f.pessoa_dupls_id','f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
                'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.id as idfor','p.nome as nomefor')
                ->where('f.pessoa_dupls_id', 'LIKE', $idfornecedor_filtro)
                ->where('f.pagamento', '!=', null)
                ->whereBetween('f.pagamento', [$dtInicial, $dtFinal])
                ->where('f.pagar_receber','=','Pagar')
                ->orderBy('f.pagamento','asc')
                ->get();

                $total_relatorio = $pagamentos->sum('valor_liquido');

        }

        //dd($pagamentos,$idfornecedor_filtro);

         if ($idTodosFornecedores=='Todos') {
            foreach ($fornecedores as $for) {
                $codigo_fornecedor=$for->id;

                $pagamentos=DB::table('financeiros as f')
                ->join('classificacaos as c', 'f.classificacao_id', '=', 'c.id')
                ->join('pessoa_dupls as p', 'f.pessoa_dupls_id', '=', 'p.id')
                ->select('f.id','f.duplicata','f.nf','f.tipo','valor','f.valor_liquido','f.pgto_conta',
                'f.juros','f.desconto','f.pagamento','f.vencimento','c.nome as nomeclas','p.id as idfor','p.nome as nomefor')
                ->where('f.pagamento', '!=', null)
                ->whereBetween('f.pagamento', [$dtInicial, $dtFinal])
                ->where('f.pagar_receber','=','Pagar')
                ->orderBy('f.pagamento','asc')
                ->get();

                 $total_relatorio = $pagamentos->sum('valor_liquido');

                // echo " Chave " .$pro->idproprietario. " - ";
                // echo "         Proprietario: " . $codigo_prop . "</br>";
                // echo "         Proprietario: " . $pro->nome . "</br>";
                // echo "         Recibo: " . $recibos[0]->idproprietario . "</br>";
                // echo "         ====================== " ."</br>";
                
            } //foreach ($proprietarios as $pro{
        } //if ($idTodosProprietarios=='Todos') {

            $codigorofornecedor=$fornecedores[0]->id;
            $nomefornecedor=$fornecedores[0]->nome;

           // dd($pagamentos);

            $pdf = PDF::loadView('reports/duplPagarBaixa/pdf_FinBaixa',compact('empresas','pagamentos','total_relatorio',
            'dtInicial','dtFinal','nomefornecedor','codigorofornecedor'));
            return $pdf->setPaper('a4')->stream('todas_Dupl_baixadas.pdf');

        }
}
