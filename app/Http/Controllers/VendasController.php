<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Imovel;
use App\Proprietario;
use App\Municipio;
use App\PessoaDupl;
use App\Financeiro;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ImovelFormRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class VendasController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

         $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

    	if($request){
    		$query=trim($request->get('searchText'));
    		$imoveis=DB::table('imovel as i')
            ->join('proprietario as p', 'i.idproprietario', '=', 'p.idproprietario')
            ->leftjoin('pessoa_dupls as e', 'i.pessoa_dupls_id', '=', 'e.id')
    		->select('i.idimovel','i.idproprietario','p.nome as nomepro','i.idmunicipio','i.nome',
            'i.endereco','i.complemento','i.bairro','i.cidade','i.uf','i.cep','i.referencia','i.obs',
            'i.condicao','i.codigo','i.situacao','i.idlocacao','i.valor_locacao','i.valor_venda',
            'i.condicao2','e.id as idcor','e.nome as nomecor','i.dt_venda')
            ->where('i.dt_venda','=',null) 
            ->where('i.condicao','=','Ativo') 
            //->where('i.status','=',null) 
            ->where('i.status','!=','Locacao') 
            ->orderBy('i.idimovel','desc')
    		->get();

            $imoveisVendidos=DB::table('imovel as i')
            ->join('proprietario as p', 'i.idproprietario', '=', 'p.idproprietario')
            ->leftjoin('pessoa_dupls as e', 'i.pessoa_dupls_id', '=', 'e.id')
    		->select('i.idimovel','i.idproprietario','p.nome as nomepro','i.idmunicipio','i.nome',
            'i.endereco','i.complemento','i.bairro','i.cidade','i.uf','i.cep','i.referencia','i.obs',
            'i.condicao','i.codigo','i.situacao','i.idlocacao','i.valor_locacao','i.valor_venda',
            'i.condicao2','e.id as idcor','e.nome as nomecor','i.dt_venda')
            ->where('i.dt_venda','!=',null) 
            ->where('i.status','!=','Locacao') 
            ->orderBy('i.idimovel','desc')
    		->get();


    		return view('tabela.venda.index', [
    			"imoveis"=>$imoveis,
    			"imoveisVendidos"=>$imoveisVendidos,
                "Proprietario"=>$proprietarios,
                "empresas"=>$empresas,
                 "searchText"=>$query
    			]);
    	}
    }

    public function show($id){

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        return view("tabela.imovel.show", 
    		["imovel"=>Imovel::findOrFail($id),
            "proprietarios"=>$proprietarios]);
    }

    public function edit($id){

        $imoveis=DB::table('imovel as i')
        ->join('proprietario as p', 'i.idproprietario', '=', 'p.idproprietario')
        ->leftjoin('pessoa_dupls as e', 'i.pessoa_dupls_id', '=', 'e.id')
        ->select('i.idimovel','i.idproprietario','p.nome as nomepro','i.idmunicipio','i.nome',
        'i.endereco','i.complemento','i.bairro','i.cidade','i.uf','i.cep','i.referencia','i.obs',
        'i.condicao','i.codigo','i.situacao','e.id as idcor','e.nome as nomecor',
        'i.pessoa_dupls_id',
        'i.tipo',
        'i.status',
        'i.condicao2',
        'i.area',
        'i.area_construida',
        'i.quartos',
        'i.banheiros',
        'i.suites',
        'i.garagens',
        'i.piscinas',
        'i.ano_imovel',
        'i.valor_locacao',
        'i.valor_venda',
        'i.iptu',
        'i.condiminio',
        'i.comissao_adm',
        'i.comissao_corretor',
        'i.validade',
        'i.dt_inicial',
        'i.dt_final',
        'i.dt_venda',
        'i.descricao_imovel',
        'i.descricao_venda',
        'i.img_principal',
        'i.img_banner',
        'i.img_planta',
        'i.url_youtube',
        
        )
        ->where('i.idimovel','=',$id)
        ->get();

         $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        $municipios=DB::table('municipio')->get();

        $corretores=DB::table('pessoa_dupls as p')
        ->where('p.for_cli','=','Corretor')
        ->where('p.condicao','=','Ativo')
        ->get();

//            ["imovel"=>imovel::findOrFail($id),
  	return view("tabela.venda.edit", 
    		["imovel"=>imovel::findOrFail($id),
            "imoveis"=>$imoveis,
            "proprietarios"=>$proprietarios,
            "corretores"=>$corretores,
            "municipios"=>$municipios]);
    }

    public function update(ImovelFormRequest $request, $id){
    	$imovel=imovel::findOrFail($id);
        $imovel->dt_venda=$request->get('dt_venda');
        $imovel->descricao_venda=$request->get('descricao_venda');
    	$imovel->update();

        
        $valorVenda=$imovel->valor_venda;
        $comissao_corretor=$imovel->comissao_corretor;
        $comissao=$valorVenda * $comissao_corretor /100;

        $pagar = new financeiro;
        $pagar->pessoa_dupls_id=$imovel->pessoa_dupls_id;
        $pagar->pessoa_vend_id='1';
        $pagar->classificacao_id='1';
        $pagar->imovel_id=$imovel->idimovel;
        $pagar->duplicata=$imovel->idimovel;
        $pagar->contabil='NAO';
        $pagar->tipo='Obrigacao';
        $pagar->valor=$comissao;
        $pagar->vencimento=$imovel->dt_venda;
        $pagar->historico='Comissão de Venda';
        $pagar->valor_liquido= $comissao;
        $pagar->pagar_receber='Pagar';
        $pagar->save();

        $valorVenda=$imovel->valor_venda;
        $comissao_adm=$imovel->comissao_adm;
        $comissao=$valorVenda * $comissao_adm /100;

        $receber = new financeiro;
        $receber->pessoa_dupls_id='1';
        $receber->pessoa_vend_id='1';
        $receber->classificacao_id='1';
        $receber->imovel_id=$imovel->idimovel;
        $receber->duplicata=$imovel->idimovel;
        $receber->contabil='NAO';
        $receber->tipo='Obrigacao';
        $receber->valor=$comissao;
        $receber->vencimento=$imovel->dt_venda;
        $receber->historico='Comissão de Venda';
        $receber->valor_liquido= $comissao;
        $receber->pagar_receber='Receber';
        $receber->save();



    	return Redirect::to('tabela/venda');
    }

    public function destroy($id){
    	$imovel=imovel::findOrFail($id);
    	$imovel->condicao='Inativo';
        $imovel->situacao='Vago_Inativo';
    	$imovel->update();
    	return Redirect::to('tabela/venda');
    }

    private function parseDate($date, $plusDay = false)
    {
        if ($plusDay == false)
            return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
        else
            return date('Y-m-d', strtotime("+1 day", strtotime(str_replace("/", "-", $date))));
    }

}
