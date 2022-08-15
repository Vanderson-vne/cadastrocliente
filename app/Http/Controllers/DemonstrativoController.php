<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Recibo;
use App\DetalheRecibo;
use App\Movimentacao;
use App\User;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ReciboFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use PDF;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class DemonstrativoController extends Controller
{
    public function __construct(){
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

                $imoveis=DB::table('imovel')->get();
                $municipios=DB::table('municipio')->get();

                $inquilinos=DB::table('inquilino as i')
                    ->join('proprietario as p', 'i.idproprietario', '=', 'p.idproprietario')
                    ->join('imovel as im', 'i.idimovel', '=', 'im.idimovel')
                    ->select('i.idinquilino','i.idproprietario','p.nome as nomepro','i.idimovel','i.idmunicipio','i.tipo_pessoa','i.nome','i.fantasia','i.fisica_juridica','i.cpf_cnpj','i.endereco as endinq','i.telefone','i.email','i.complemento','i.bairro','i.cidade','i.uf','i.cep','i.referencia','i.obs','i.rg_ie','i.condicao','i.conjuge','i.aos_cuidados','i.end_corr','i.num_corr','i.compl_corr','i.bairro_corr','i.cidade_corr','i.uf_corr','i.cep_corr','i.favorecido','i.cpf_fav','i.banco_fav','i.ag_fav','i.conta_fav','i.ult_extrato','i.data_ult_extrato','i.irrf','i.locacao_encerada','i.dt_enc_locacao','i.ult_recibo','im.endereco as endimovel')
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
                    ->select('r.idrecibo','r.mes_ano','r.codigo','r.forma_pgto' ,'r.dt_inicial','r.dt_final','r.total_aluguel' ,'l.idlocacao', 'i.nome as nomeinq','i.idinquilino as codinq','i.cpf_cnpj as cnpjcpfinq','i.telefone as foneinq','p.nome as nomepro', 'p.idproprietario','p.cpf_cnpj as cnpjcpfpro','p.telefone as fonepro' ,'im.idimovel' ,'im.codigo as codigoimo','im.endereco','im.bairro','im.cidade','in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.taxa_adm','r.liquido')
                    ->orderBy('r.dt_vencimento','desc')
                    ->get();
                    return view('reports.demonstrativo.index', [
                        "recibos"=>$recibos,
                        "proprietarios"=>$proprietarios,
                        "imoveis"=>$imoveis,
                        "inquilinos"=>$inquilinos,
                        "empresas"=>$empresas,
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

            $imoveis=DB::table('imovel')->get();
            $municipios=DB::table('municipio')->get();

            $inquilinos=DB::table('inquilino as i')
                ->join('proprietario as p', 'i.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'i.idimovel', '=', 'im.idimovel')
                ->select('i.idinquilino','i.idproprietario','p.nome as nomepro','i.idimovel','i.idmunicipio','i.tipo_pessoa','i.nome','i.fantasia','i.fisica_juridica','i.cpf_cnpj','i.endereco as endinq','i.telefone','i.email','i.complemento','i.bairro','i.cidade','i.uf','i.cep','i.referencia','i.obs','i.rg_ie','i.condicao','i.conjuge','i.aos_cuidados','i.end_corr','i.num_corr','i.compl_corr','i.bairro_corr','i.cidade_corr','i.uf_corr','i.cep_corr','i.favorecido','i.cpf_fav','i.banco_fav','i.ag_fav','i.conta_fav','i.ult_extrato','i.data_ult_extrato','i.irrf','i.locacao_encerada','i.dt_enc_locacao','i.ult_recibo','im.endereco as endimovel')
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
                ->select('r.idrecibo','r.mes_ano','r.codigo','r.forma_pgto' ,'r.dt_inicial','r.dt_final','r.total_aluguel' ,'l.idlocacao', 'i.nome as nomeinq','i.idinquilino as codinq','i.cpf_cnpj as cnpjcpfinq','i.telefone as foneinq','p.nome as nomepro', 'p.idproprietario','p.cpf_cnpj as cnpjcpfpro','p.telefone as fonepro' ,'im.idimovel' ,'im.codigo as codigoimo','im.endereco','im.bairro','im.cidade','in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.taxa_adm','r.liquido')
                ->where('r.idproprietario','=', $idproprietario)
                ->orderBy('r.dt_vencimento','desc')
                ->get();

                return view('reports.demonstrativo.index', [
                    "recibos"=>$recibos,
                    "proprietarios"=>$proprietarios,
                    "imoveis"=>$imoveis,
                    "inquilinos"=>$inquilinos,
                    "empresas"=>$empresas,
                    "searchText"=>$query
                    ]);
            }
    }

    }


     public function PdfDemonstrativo(Request $request){
        
        $empresas=DB::table('empresa as e')->get();

        $mesano=trim($request->get('mesano'));
        
        $dtInicial=trim($request->get('dtVectoInicial'));
        $dtFinal=trim($request->get('dtVectoFinal'));

        $idinquilino_filtro=trim($request->get('idpinquilino'));
        $idproprietario_filtro=trim($request->get('idproprietario'));
    
        $dados=$request->get('idpinquilino');
        $array = explode('_', $dados);
        $idTodosinquilinos=$array[0];

        $dados=$request->get('idproprietario');
        $array = explode('_', $dados);
        $idTodosProprietarios=$array[0];

        $saldo=1;
        if ($dtInicial=="") {
           $saldo=0;
           $dtInicial='2001-01-01';
       }
       if ($dtFinal=="") {
           $dtFinal='4001-01-01';
       }


        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();


        $proprietarios=DB::table('proprietario as p')
        ->where('p.idproprietario', 'LIKE', $idproprietario_filtro)
        ->get();

        $indices=DB::table('indice as ind')->get();
       

         if ($idTodosProprietarios!='Todos') {
                $recibos = DB::table('recibo as r')
                ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                ->select('r.idrecibo','r.contador_aluguel','r.valor_pgto','r.reajuste','r.mes_ano','r.dt_inicial','r.dt_final','r.total_aluguel' ,'l.idlocacao', 'i.nome as nomeinq','i.idinquilino as codinq','i.cpf_cnpj as cnpjcpfinq','i.telefone as foneinq','p.nome as nomepro', 'p.idproprietario','p.cpf_cnpj as cnpjcpfpro','p.telefone as fonepro' ,'im.idimovel' ,'im.codigo as codigoimo','im.endereco','im.bairro','im.cidade','im.nome as tipoimovel','in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.taxa_adm','r.liquido')
                ->where('r.dt_pagamento', '!=', Null)
                ->where('r.mes_ano', '=', $mesano)
                ->where('r.idproprietario', 'LIKE', $idproprietario_filtro)
                //->orderBy('r.dt_vencimento','asc')
                ->get();

                 $total_relatorio = $recibos->sum('total_aluguel');
                 $total_taxa_adm = $recibos->sum('taxa_adm');
                 $total_liquido = $recibos->sum('liquido');

                $imoveis=DB::table('imovel as im')
                ->where('im.idproprietario','=',$idproprietario_filtro)
                ->where('im.condicao','=','Ativo')
                ->where('im.situacao','=','Vago')
                ->get();

                //dd($recibos);

                // if ($total_relatorio) {
                //     $pdf = PDF::loadView('reports/demonstrativo/pdf_demonstrativo',compact('recibos','imoveis','total_relatorio','total_taxa_adm','total_liquido'));
                //     return $pdf->setPaper('a4')->stream('todos_demonstrativo.pdf');
                // }
                //  $total_relatorio = '0';
        }

         if ($idTodosProprietarios=='Todos') {
            foreach ($proprietarios as $pro) {
                $codigo_prop=$pro->idproprietario;

                $recibos = DB::table('recibo as r')
                ->leftjoin('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->leftjoin('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                ->leftjoin('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                ->leftjoin('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                ->leftjoin('indice as in', 'r.idindice', '=', 'in.idindice')
                ->select('r.idrecibo','r.mes_ano','r.dt_inicial','r.dt_final','r.total_aluguel' ,'l.idlocacao',
                 'i.nome as nomeinq','i.idinquilino as codinq','i.cpf_cnpj as cnpjcpfinq','i.telefone as foneinq',
                 'p.nome as nomepro', 'p.idproprietario','p.cpf_cnpj as cnpjcpfpro','p.telefone as fonepro' ,
                 'im.idimovel' ,'im.codigo as codigoimo','im.endereco','im.bairro','im.cidade','in.nome as nomeind',
                 'r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento',
                 'r.dt_pagamento','r.taxa_adm','r.liquido')
                ->where('r.dt_pagamento', '!=', Null)
                ->where('r.mes_ano', '=', $mesano)
                ->where('r.idproprietario', '=',$codigo_prop)
                //->orderBy('r.dt_vencimento','asc')
                ->get();
                
                 $total_relatorio = $recibos->sum('total_aluguel');
                 $total_taxa_adm = $recibos->sum('taxa_adm');
                 $total_liquido = $recibos->sum('liquido');

                $imoveis=DB::table('imovel as im')
                ->where('im.idproprietario','=',$codigo_prop)
                ->where('im.condicao','=','Ativo')
                ->where('im.situacao','=','Vago')
                ->get();

                // echo " Chave " .$pro->idproprietario. " - ";
                // echo "         Proprietario: " . $codigo_prop . "</br>";
                // echo "         Proprietario: " . $pro->nome . "</br>";
                // echo "         Recibo: " . $recibos[0]->idproprietario . "</br>";
                // echo "         ====================== " ."</br>";
                
            } //foreach ($proprietarios as $pro{
        } //if ($idTodosProprietarios=='Todos') {


/////////////Extrato de Conta Corrente
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
            ->where('m.idproprietario','=', $idproprietario_filtro)
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

        //  echo $idmov.' ID<br>';
        //  echo $tipo.' Tipo_d_c<br>';
        //  echo $saldo.' Saldo<br>';
        //  echo $detalhe->parcial.' parcial<br>';
        //  echo $detalhe->valor.' valor<br>';
        //  echo '============================================<br>';    

            foreach ($mov_de_contas as $det) {
                $idmov=$det->id;
                $detalhe=Movimentacao::findOrFail($idmov);
                $tipo=$det->Tipo_d_c;

        //  echo $saldo.' Antes Saldo<br>';

                if ($tipo == 'Credito') {
                    if ($saldo != 0) {
                        $saldo=$saldo+$detalhe->valor;
                        $detalhe->parcial=$saldo;
                    }
                    if ($saldo == 0) {
                        $saldo=$saldo+$detalhe->valor;
                        $detalhe->parcial=$saldo;
                    }
        //  echo $saldo.' Saldo apos Credito<br>';
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

            //   echo $idmov.' ID<br>';
            //   echo $tipo.' Tipo_d_c<br>';
            //   echo $saldo.' Saldo<br>';
            //   echo $detalhe->parcial.' parcial<br>';
            //   echo $detalhe->valor.' valor<br>';
            //   echo '============================================<br>';    

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
            ->where('m.idproprietario','=', $idproprietario_filtro)
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
            ->where('m.idproprietario','=', $idproprietario_filtro)
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
            ->where('m.idproprietario','=', $idproprietario_filtro)
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

            $total_relatorio_extrato = $mov_de_contas->sum('valor');


           // dd($recibos);

            if ($recibos->isEmpty()){
                $pdf = PDF::loadView('reports/demonstrativo/pdf_demonstrativo_vago',compact('empresas','proprietarios','imoveis','total_relatorio','total_taxa_adm','total_liquido','mov_de_contas',
                'total_relatorio_extrato','dtInicial','dtFinal','nomeproprietario','codigoroprietario','total_credito','total_debito'));
                return $pdf->setPaper('a4')->stream('todos_demonstrativo.pdf');
            } else {
                $pdf = PDF::loadView('reports/demonstrativo/pdf_demonstrativo',compact('empresas','recibos','imoveis','total_relatorio','total_taxa_adm','total_liquido','mov_de_contas',
                'total_relatorio_extrato','dtInicial','dtFinal','nomeproprietario','codigoroprietario','total_credito','total_debito'));
                return $pdf->setPaper('a4')->stream('todos_demonstrativo.pdf');
            }

        }

}
