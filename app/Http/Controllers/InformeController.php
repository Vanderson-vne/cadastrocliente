<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Recibo;
use App\DetalheRecibo;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ReciboFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class InformeController extends Controller
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
                        ->where('r.dt_pagamento', '=', '')->orWhereNull('r.dt_pagamento')
                        ->orwhere('r.idlocacao', 'LIKE', '%'.$query.'%')
                        ->orwhere('r.idrecibo', 'LIKE', '%'.$query.'%')
                        ->orwhere('r.codigo', 'LIKE', '%'.$query.'%')
                        ->orwhere('r.mes_ano', 'LIKE', '%'.$query.'%')
                        ->orwhere('r.dt_vencimento', '<=', '%'.$query.'%')
                        ->orwhere('im.codigo', 'LIKE', '%'.$query.'%')
                        ->orwhere('im.endereco', 'LIKE', '%'.$query.'%')
                        ->orwhere('i.nome', 'LIKE', '%'.$query.'%')
                        ->orwhere('p.nome', 'LIKE', '%'.$query.'%')
                        ->orWhereNull('r.dt_pagamento')
                        ->orderBy('r.dt_vencimento','desc')
                        ->get();
                        return view('reports.informe.index', [
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
                ->where('r.dt_pagamento', '=', '')->orWhereNull('r.dt_pagamento')
                ->where('r.idproprietario','=', $idproprietario)
                ->orwhere('r.idlocacao', 'LIKE', '%'.$query.'%')
                ->orwhere('r.idrecibo', 'LIKE', '%'.$query.'%')
                ->orwhere('r.codigo', 'LIKE', '%'.$query.'%')
                ->orwhere('r.mes_ano', 'LIKE', '%'.$query.'%')
                ->orwhere('r.dt_vencimento', '<=', '%'.$query.'%')
                ->orwhere('im.codigo', 'LIKE', '%'.$query.'%')
                ->orwhere('im.endereco', 'LIKE', '%'.$query.'%')
                ->orwhere('i.nome', 'LIKE', '%'.$query.'%')
                ->orwhere('p.nome', 'LIKE', '%'.$query.'%')
                ->orWhereNull('r.dt_pagamento')
                ->orderBy('r.dt_vencimento','desc')
                ->get();
                return view('reports.informe.index', [
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


     public function PdfInforme(Request $request){

        $ano=trim($request->get('ano'));
        $idinquilino_filtro=trim($request->get('idpinquilino'));
        $idproprietario_filtro=trim($request->get('idproprietario'));

        $dados=$request->get('idproprietario');
        $array = explode('_', $dados);
        $idTodosProprietarios=$array[0];

        $empresas=DB::table('empresa as emp')
        ->get();

        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        // $value=  Carbon::create($dtInicial);
        // $dia = substr($value, 8, 2);
        // $mes = substr($value, 5, 2);
        // $ano = substr($value, 0, 4);


        $meses[1]['mes']='Janeiro/'.$ano;
        $meses[1]['ano']=$ano;
        $meses[1]['data1']=$ano.'-'.'01-01'; //'01/01/'.$ano;
        $meses[1]['data2']=$ano.'-'.'01-31'; //'31/01/'.$ano;
        $meses[1]['aluguel']='';
        $meses[1]['ir']='';
        $meses[1]['taxa']='';
        $meses[1]['liquido']='';

        $meses[2]['mes']='Fevereiro/'.$ano;
        $meses[2]['ano']=$ano;
        $meses[2]['data1']=$ano.'-'.'02-01'; 
        $meses[2]['data2']=$ano.'-'.'02-28'; 
        $meses[2]['aluguel']='';
        $meses[2]['ir']='';
        $meses[2]['taxa']='';
        $meses[2]['liquido']='';

        $meses[3]['mes']='Março/'.$ano;
        $meses[3]['ano']=$ano;
        $meses[3]['data1']=$ano.'-'.'03-01'; 
        $meses[3]['data2']=$ano.'-'.'03-31'; 
        $meses[3]['aluguel']='';
        $meses[3]['ir']='';
        $meses[3]['taxa']='';
        $meses[3]['liquido']='';

        $meses[4]['mes']='Abril/'.$ano;
        $meses[4]['ano']=$ano;
        $meses[4]['data1']=$ano.'-'.'04-01'; 
        $meses[4]['data2']=$ano.'-'.'04-31'; 
        $meses[4]['aluguel']='';
        $meses[4]['ir']='';
        $meses[4]['taxa']='';
        $meses[4]['liquido']='';

        $meses[5]['mes']='Maio/'.$ano;
        $meses[5]['ano']=$ano;
        $meses[5]['data1']=$ano.'-'.'05-01'; 
        $meses[5]['data2']=$ano.'-'.'05-30'; 
        $meses[5]['aluguel']='';
        $meses[5]['ir']='';
        $meses[5]['taxa']='';
        $meses[5]['liquido']='';

        $meses[6]['mes']='Junho/'.$ano;
        $meses[6]['ano']=$ano;
        $meses[6]['data1']=$ano.'-'.'06-01'; 
        $meses[6]['data2']=$ano.'-'.'06-30'; 
        $meses[6]['aluguel']='';
        $meses[6]['ir']='';
        $meses[6]['taxa']='';
        $meses[6]['liquido']='';

        $meses[7]['mes']='Julho/'.$ano;
        $meses[7]['ano']=$ano;
        $meses[7]['data1']=$ano.'-'.'07-01'; 
        $meses[7]['data2']=$ano.'-'.'07-31'; 
        $meses[7]['aluguel']='';
        $meses[7]['ir']='';
        $meses[7]['taxa']='';
        $meses[7]['liquido']='';

        $meses[8]['mes']='Agosto/'.$ano;
        $meses[8]['ano']=$ano;
        $meses[8]['data1']=$ano.'-'.'08-01'; 
        $meses[8]['data2']=$ano.'-'.'08-31'; 
        $meses[8]['aluguel']='';
        $meses[8]['ir']='';
        $meses[8]['taxa']='';
        $meses[8]['liquido']='';

        $meses[9]['mes']='Setembro/'.$ano;
        $meses[9]['ano']=$ano;
        $meses[9]['data1']=$ano.'-'.'09-01'; 
        $meses[9]['data2']=$ano.'-'.'09-30'; 
        $meses[9]['aluguel']='';
        $meses[9]['ir']='';
        $meses[9]['taxa']='';
        $meses[9]['liquido']='';

        $meses[10]['mes']='Outubro/'.$ano;
        $meses[10]['ano']=$ano;
        $meses[10]['data1']=$ano.'-'.'10-01'; 
        $meses[10]['data2']=$ano.'-'.'10-31'; 
        $meses[10]['aluguel']='';
        $meses[10]['ir']='';
        $meses[10]['taxa']='';
        $meses[10]['liquido']='';

        $meses[11]['mes']='Novembro/'.$ano;
        $meses[11]['ano']=$ano;
        $meses[11]['data1']=$ano.'-'.'11-01'; 
        $meses[11]['data2']=$ano.'-'.'11-31'; 
        $meses[11]['aluguel']='';
        $meses[11]['ir']='';
        $meses[11]['taxa']='';
        $meses[11]['liquido']='';

        $meses[12]['mes']='Dezembro/'.$ano;
        $meses[12]['ano']=$ano;
        $meses[12]['data1']=$ano.'-'.'12-01'; 
        $meses[12]['data2']=$ano.'-'.'12-31'; 
        $meses[12]['aluguel']='';
        $meses[12]['ir']='';
        $meses[12]['taxa']='';
        $meses[12]['liquido']='';

          
        foreach ($meses as $key  => $value ) {

            $dtInicial= $value[('data1')];
            $dtFinal= $value[('data2')];

            $indices=DB::table('indice as ind')->get();

            $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'r.idindice', '=', 'in.idindice')
            ->select('r.idrecibo','r.mes_ano','r.dt_inicial',
            'r.dt_final','r.total_aluguel' ,'l.idlocacao', 'i.nome as nomeinq',
            'i.idinquilino as codinq','i.cpf_cnpj as cnpjcpfinq','i.telefone as foneinq',
            'p.nome as nomepro', 'p.idproprietario','p.cpf_cnpj as cnpjcpfpro','p.telefone as fonepro' ,
            'im.idimovel' ,'im.codigo as codigoimo','im.endereco','im.bairro','im.cidade','im.cep',
            'in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste',
            'l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.taxa_adm','r.liquido')
            ->where('r.dt_pagamento', '!=', Null)
            ->whereBetween('r.dt_pagamento', [$dtInicial,$dtFinal])
            ->where('r.idproprietario', '=', $idproprietario_filtro)
            ->orderBy('r.dt_pagamento','asc')
            ->get();

             $total_relatorio = $recibos->sum('total_aluguel');
             $total_taxa_adm = $recibos->sum('taxa_adm');
             $total_liquido = $recibos->sum('liquido');
            
            $valorIr=0;
            if ($recibos->isEmpty()){
            } else {
                $idrecibo=$recibos[0]->idrecibo;
                
                $detablherec=DB::table('detalhe_recibo as det')
                ->where('det.idrecibo','=',$idrecibo)
                ->where('det.idevento','=','4')
                ->get();

                if ($detablherec->isEmpty()){
                } else {
                    $valorIr=$detablherec[0]->valor;
                }
            }

             $imoveis=DB::table('imovel as im')
             ->where('im.idproprietario','=',$idproprietario_filtro)
             ->where('im.condicao','=','Ativo')
             ->where('im.situacao','=','Vago')
             ->get();


             $meses[$key]['aluguel']=$total_relatorio;
             $meses[$key]['ir']=$valorIr;
             $meses[$key]['taxa']=$total_taxa_adm;
             $meses[$key]['liquido']=$total_liquido;
 
 
            // echo "Mes: " . $value['mes']." Chave " .$key. " - ";
            // echo "         Proprietario: " . $idproprietario_filtro . "";
            // echo "         Data Ini: " . $value['data1'] . "";
            // echo "         Data Fin " . $value['data2'] . "</br>";
            // echo "         Data ini Banco " . $dtInicial . "";
            // echo "         Data Fin Banco " . $dtFinal . "</br>";
            // echo "         total_relatorio " . $total_relatorio . "</br>";
            // echo "         total_taxa_adm " . $total_taxa_adm . "</br>";
            // echo "         total_liquido " . $total_liquido . "</br>";
            // //echo "         Aluguel " . $recibos[0]->total_aluguel . "</br>";
            // echo "         ====================== " ."</br>";

        }

       // $meses = json_encode($meses, true);

       $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'r.idindice', '=', 'in.idindice')
            ->select('r.idrecibo','r.mes_ano','r.dt_inicial',
            'r.dt_final','r.total_aluguel' ,'l.idlocacao', 'i.nome as nomeinq',
            'i.idinquilino as codinq','i.cpf_cnpj as cnpjcpfinq','i.telefone as foneinq',
            'p.nome as nomepro', 'p.idproprietario','p.cpf_cnpj as cnpjcpfpro','p.telefone as fonepro' ,
            'im.idimovel' ,'im.codigo as codigoimo','im.endereco','im.bairro','im.cidade','im.cep',
            'in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste',
            'l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.taxa_adm','r.liquido')
            ->where('r.dt_pagamento', '!=', Null)
            //->whereBetween('r.dt_pagamento', [$dtInicial,$dtFinal])
            ->where('r.idproprietario', '=', $idproprietario_filtro)
            ->orderBy('r.dt_pagamento','asc')
            ->get();

       //dd($recibos,$meses);

         $pdf = PDF::loadView('reports/informe/pdf_informe',compact('recibos','imoveis','meses','empresas'));
         return $pdf->setPaper('a4')->stream('todos_informes.pdf');
         $total_relatorio = '0';

         
       
       //return PDF::loadFile(public_path().'/myfile.html')->save('/path-to/my_stored_file.pdf')->stream('download.pdf');
    //    $indices=DB::table('indice as ind')->get();
    //
    //     if ($idTodosProprietarios!='Todos') {
    //            $recibos = DB::table('recibo as r')
    //            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
    //            ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
    //            ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
    //            ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
    //            ->join('indice as in', 'l.idindice', '=', 'in.idindice')
    //            ->select('r.idrecibo','r.mes_ano','r.dt_inicial','r.dt_final','r.total_aluguel' ,'l.idlocacao', 'i.nome as nomeinq','i.idinquilino as codinq','i.cpf_cnpj as cnpjcpfinq','i.telefone as foneinq','p.nome as nomepro', 'p.idproprietario','p.cpf_cnpj as cnpjcpfpro','p.telefone as fonepro' ,'im.idimovel' ,'im.codigo as codigoimo','im.endereco','im.bairro','im.cidade','im.cep','in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.taxa_adm','r.liquido')
    //            ->where('r.dt_pagamento', '=', '')->orWhereNull('r.dt_pagamento')
    //            ->whereBetween('r.dt_vencimento', [$dtInicial,$dtFinal])
    //            ->where('r.idproprietario', 'LIKE', $idproprietario_filtro)
    //            ->orderBy('r.dt_vencimento','asc')
    //            ->get();
    //             $total_relatorio = $recibos->sum('total_aluguel');
    //             $total_taxa_adm = $recibos->sum('taxa_adm');
    //             $total_liquido = $recibos->sum('liquido');

    //            $imoveis=DB::table('imovel as im')
    //            ->where('im.idproprietario','=',$idproprietario_filtro)
    //            ->where('im.condicao','=','Ativo')
    //            ->where('im.situacao','=','Vago')
    //            ->get();


    //            if ($total_relatorio) {
    //                $pdf = PDF::loadView('reports/demonstrativo/pdf_demonstrativo',compact('recibos','imoveis','total_relatorio','total_taxa_adm','total_liquido'));
    //                return $pdf->setPaper('a4')->stream('todos_demonstrativo.pdf');
    //            }
    //             $total_relatorio = '0';
    //    }

    //     if ($idTodosProprietarios=='Todos') {

    //        foreach ($proprietarios as $pro) {
    //             $recibos = DB::table('recibo as r')
    //            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
    //            ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
    //            ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
    //            ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
    //            ->join('indice as in', 'l.idindice', '=', 'in.idindice')
    //            ->select('r.idrecibo','r.mes_ano','r.dt_inicial','r.dt_final','r.total_aluguel' ,'l.idlocacao', 'i.nome as nomeinq','i.idinquilino as codinq','i.cpf_cnpj as cnpjcpfinq','i.telefone as foneinq','p.nome as nomepro', 'p.idproprietario','p.cpf_cnpj as cnpjcpfpro','p.telefone as fonepro' ,'im.idimovel' ,'im.codigo as codigoimo','im.endereco','im.bairro','im.cidade','im.cep','in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.taxa_adm','r.liquido')
    //            ->where('r.dt_pagamento', '=', '')->orWhereNull('r.dt_pagamento')
    //            ->whereBetween('r.dt_vencimento', [$dtInicial,$dtFinal])
    //            ->where('r.idproprietario', 'LIKE', $pro->idproprietario)
    //            ->orderBy('r.dt_vencimento','asc')
    //            ->get();
    //             $total_relatorio = $recibos->sum('total_aluguel');
    //             $total_taxa_adm = $recibos->sum('taxa_adm');
    //             $total_liquido = $recibos->sum('liquido');

    //            $imoveis=DB::table('imovel as im')
    //            ->where('im.idproprietario','=',$pro->idproprietario)
    //            ->where('im.condicao','=','Ativo')
    //            ->where('im.situacao','=','Vago')
    //            ->get();

    //            if ($total_relatorio) {
    //                $pdf = PDF::loadView('reports/informe/pdf_informe',compact('recibos','imoveis','total_relatorio','total_taxa_adm','total_liquido'));
    //                return $pdf->setPaper('a4')->stream('todos_informes.pdf');
    //            }

    //             $total_relatorio = '0';

     //       } //foreach ($proprietarios as $pro{
    //    } //if ($idTodosProprietarios=='Todos') {


    }

}
