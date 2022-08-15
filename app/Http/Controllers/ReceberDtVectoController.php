<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Recibo;
use App\DetalheRecibo;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ReciboFormRequest;
use Illuminate\Support\Facades\DB;
use PDF;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class ReceberDtVectoController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){
    
        //dd($request);

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
            $dtInicial=trim($request->get('dtVectoInicial'));
            $dtFinal=trim($request->get('dtVectoFinal'));
            $query=trim($request->get('searchText'));

            if ($dtInicial=="") {
                $dtInicial='2001-01-01';
            }
            if ($dtFinal=="") {
                $dtFinal=Carbon::now(); //'4001-01-01';
                //$dtFinal='4001-01-01';
            }

    		$recibos = DB::table('recibo as r')
    		->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'r.idindice', '=', 'in.idindice')
    		->select('r.idrecibo','r.mes_ano','r.codigo','r.forma_pgto' ,'r.dt_inicial','r.dt_final','r.total_aluguel' ,'l.idlocacao', 'i.nome as nomeinq','i.idinquilino as codinq','i.cpf_cnpj as cnpjcpfinq','i.telefone as foneinq','p.nome as nomepro', 'p.idproprietario','p.cpf_cnpj as cnpjcpfpro','p.telefone as fonepro' ,'im.idimovel' ,'im.codigo as codigoimo','im.endereco','im.bairro','im.cidade','in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.taxa_adm','r.liquido','r.forma_pgto')
            ->where('r.dt_pagamento', '=', '"0000-00-00 00:00:00"')->orWhereNull('r.dt_pagamento')
            ->whereBetween('r.dt_vencimento', [$dtInicial,$dtFinal])
            ->where('r.idlocacao', 'LIKE', '%'.$query.'%')
            ->where('r.idrecibo', 'LIKE', '%'.$query.'%')
            ->where('r.codigo', 'LIKE', '%'.$query.'%')
            ->where('r.mes_ano', 'LIKE', '%'.$query.'%')
            ->where('im.codigo', 'LIKE', '%'.$query.'%')
            ->where('im.endereco', 'LIKE', '%'.$query.'%')
            ->where('i.nome', 'LIKE', '%'.$query.'%')
            ->where('p.nome', 'LIKE', '%'.$query.'%')
            ->orderBy('r.dt_vencimento','desc')
    		->get();

            //dd($recibos,$dtInicial,$dtFinal);


    		return view('reports.duplReceberDtVecto.index', [
    			"recibos"=>$recibos,
	    		"proprietarios"=>$proprietarios,
	    		"imoveis"=>$imoveis,
                "inquilinos"=>$inquilinos,
                "empresas"=>$empresas,
    			 "searchText"=>$query
    			]);
    	}
    }

    public function print(Request $request){
    
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

            $dtInicial=trim($request->get('dtVectoInicial'));
            $dtFinal=trim($request->get('dtVectoFinal'));
            $idinquilino_filtro=trim($request->get('idpinquilino'));
            $idproprietario_filtro=trim($request->get('idproprietario'));

            $query=trim($request->get('searchText'));
            $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'r.idindice', '=', 'in.idindice')
            ->select('r.taxa_adm','r.idrecibo','r.mes_ano','r.codigo','r.forma_pgto' ,'r.dt_inicial','r.dt_final','r.total_aluguel' ,'l.idlocacao', 'i.nome as nomeinq','i.idinquilino as codinq','i.cpf_cnpj as cnpjcpfinq','i.telefone as foneinq','p.nome as nomepro', 'p.idproprietario','p.cpf_cnpj as cnpjcpfpro','p.telefone as fonepro' ,'im.idimovel' ,'im.codigo as codigoimo','im.endereco','im.bairro','im.cidade','in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.taxa_adm','r.liquido')
            ->orderBy('r.dt_vencimento','desc')
            ->get();
            

            return view('reports.duplReceberDtVecto.print', [
                "recibos"=>$recibos,
                "proprietarios"=>$proprietarios,
                "imoveis"=>$imoveis,
                "inquilinos"=>$inquilinos,
                 "searchText"=>$query
                ]);
        }
    }


    public function pdfFinDtVencto(Request $request){
    
        //dd($request);

        $empresas=DB::table('empresa as e')->get();

        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->where('im.condicao','=','Ativo')->get();

        $indices=DB::table('indice as ind')->get();

        $data = Carbon::create('');
        
        if($request){
             $query=trim($request->get('searchText'));
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

            if ($dtInicial=="") {
                $dtInicial='2001-01-01';
            }
            if ($dtFinal=="") {
                $dtFinal='4001-01-01';
            }


         if ($idTodosinquilinos=='Todos' and $idTodosProprietarios=='Todos') {
              $recibos = DB::table('recibo as r')
                ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                ->select('r.taxa_adm','r.idrecibo','r.mes_ano','l.idlocacao', 'i.nome as nomeinq','i.idinquilino as codinq','i.telefone','p.nome as nomepro','im.codigo as codigoimo','im.endereco', 'in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.valor_pgto','r.forma_pgto','r.total_aluguel')
                ->where('r.dt_pagamento',Null)
                 ->whereBetween('r.dt_vencimento', [$dtInicial,$dtFinal])
                ->orderBy('r.dt_vencimento','asc')
                ->get();
               $total_relatorio = $recibos->sum('taxa_adm');
         } //if ($idTodosinquilinos=='Todos' and $idTodosProprietarios=='Todos') {


         if ($idTodosinquilinos!='Todos' and $idTodosProprietarios=='Todos') {
            $inquilinos=DB::table('inquilino as i')
            ->where('i.idinquilino',$idinquilino_filtro)
            ->get();
            
            //dd($inquilinos,$idinquilino_filtro);

            $recibos = DB::table('recibo as r')
                ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                ->select('r.taxa_adm','r.idrecibo','r.mes_ano','l.idlocacao', 'i.nome as nomeinq','i.idinquilino as codinq','i.telefone','p.nome as nomepro','im.codigo as codigoimo','im.endereco', 'in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.valor_pgto','r.forma_pgto','r.total_aluguel')
                ->where('r.dt_pagamento', '=', NULL)
                ->whereBetween('r.dt_vencimento', [$dtInicial,$dtFinal])
                ->where('r.idinquilino', 'LIKE', $idinquilino_filtro)
                ->orderBy('r.dt_vencimento','asc')
                ->get();
               $total_relatorio = $recibos->sum('taxa_adm');
               //dd($recibos,$idinquilino_filtro);

            } //if ($idTodosinquilinos=='Todos' and $idTodosProprietarios=='Todos') {


        
         if ($idTodosinquilinos=='Todos' and $idTodosProprietarios!='Todos') {
              $recibos = DB::table('recibo as r')
                ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                ->select('r.taxa_adm','r.idrecibo','r.mes_ano','l.idlocacao', 'i.nome as nomeinq','i.idinquilino as codinq','i.telefone','p.nome as nomepro','im.codigo as codigoimo','im.endereco', 'in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.valor_pgto','r.forma_pgto','r.total_aluguel')
                ->where('r.dt_pagamento', '=', NULL)
                ->whereBetween('r.dt_vencimento', [$dtInicial,$dtFinal])
                ->where('r.idproprietario', 'LIKE', $idproprietario_filtro)
                ->orderBy('r.dt_vencimento','asc')
                ->get();
               $total_relatorio = $recibos->sum('taxa_adm');

         } //if ($idTodosinquilinos=='Todos' and $idTodosProprietarios=='Todos') {

         if ($idTodosinquilinos!='Todos' and $idTodosProprietarios!='Todos') {
              $recibos = DB::table('recibo as r')
                ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                ->select('r.taxa_adm','r.idrecibo','r.mes_ano','l.idlocacao', 'i.nome as nomeinq','i.idinquilino as codinq','i.telefone','p.nome as nomepro','im.codigo as codigoimo','im.endereco', 'in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.forma_pgto','r.total_aluguel')
                ->where('r.dt_pagamento', '=', NULL)
                ->whereBetween('r.dt_vencimento', [$dtInicial,$dtFinal])
                ->where('r.idproprietario', 'LIKE', $idproprietario_filtro)
                ->where('r.idinquilino', 'LIKE', $idinquilino_filtro)
                ->orderBy('r.dt_vencimento','asc')
                ->get();
               $total_relatorio = $recibos->sum('taxa_adm');
         } //if ($idTodosinquilinos=='Todos' and $idTodosProprietarios=='Todos') {


        } //if($request){

        if ($total_relatorio) {
            $pdf = PDF::loadView('reports/duplReceberDtVecto/pdf_receber_atrasados',compact('empresas','recibos','total_relatorio','dtInicial','dtFinal'));
            return $pdf->setPaper('a4')->stream('todos_receber_atrasado.pdf');
        }

    }


}
