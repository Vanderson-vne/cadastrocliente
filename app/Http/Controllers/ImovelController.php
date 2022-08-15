<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Imovel;
use App\Proprietario;
use App\Municipio;
use App\PessoaDupl;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ImovelFormRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class ImovelController extends Controller
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
            'i.condicao2','e.id as idcor','e.nome as nomecor','i.status')
            ->where('i.endereco', 'LIKE', '%'.$query.'%')
            ->where('i.condicao','=','Ativo') 
            ->orwhere('i.codigo', 'LIKE', '%'.$query.'%')
            ->orwhere('i.situacao', 'LIKE', '%'.$query.'%')
            ->orwhere('p.nome', 'LIKE', '%'.$query.'%')
            ->orderBy('i.idimovel','desc')
    		->get();

    		return view('tabela.imovel.index', [
    			"imoveis"=>$imoveis,
                "Proprietario"=>$proprietarios,
                "empresas"=>$empresas,
                 "searchText"=>$query
    			]);
    	}
    }

    public function create(){

         $proprietarios=DB::table('proprietario')->get();
         $municipios=DB::table('municipio')->get();
         $imoveis=DB::table('imovel')->get();

         $corretores=DB::table('pessoa_dupls as p')
         ->where('p.for_cli','=','Corretor')
         ->where('p.condicao','=','Ativo')
         ->get();
 

    	return view("tabela.imovel.create",[
            "imoveis"=>$imoveis,
            "proprietarios"=>$proprietarios,
            "corretores"=>$corretores,
            "municipios"=>$municipios]);
    }
 
    public function store(ImovelFormRequest $request){
    	$imovel = new imovel;
        $imovel->idproprietario=$request->get('idproprietario');
        $imovel->idmunicipio='1'; //$request->get('idmunicipio');
        $imovel->idinquilino='0'; 
        $imovel->idlocacao='0'; 
        $imovel->nome=$request->get('nome');
    	$imovel->endereco=$request->get('endereco');
        $imovel->complemento=$request->get('complemento');
        $imovel->bairro=$request->get('bairro');
        $imovel->cidade=$request->get('cidade');
        $imovel->uf=$request->get('uf');
        $imovel->cep=$request->get('cep');
        $imovel->referencia=$request->get('referencia');
        $imovel->obs=$request->get('obs');
        $imovel->condicao='Ativo';
        $imovel->situacao='Vago';
        $imovel->codigo=$request->get('codigo');
        
        $imovel->pessoa_dupls_id=$request->get('pessoa_dupls_id');
        $imovel->tipo=$request->get('tipo');
        $imovel->status=$request->get('status');
        $imovel->condicao2=$request->get('condicao2');
        $imovel->area=$request->get('area');
        $imovel->area_construida=$request->get('area_construida');
        $imovel->quartos=$request->get('quartos');
        $imovel->banheiros=$request->get('banheiros');
        $imovel->suites=$request->get('suites');
        $imovel->garagens=$request->get('garagens');
        $imovel->piscinas=$request->get('piscinas');
        $imovel->ano_imovel=$request->get('ano_imovel');
        $imovel->valor_locacao=$request->get('valor_locacao');
        $imovel->valor_venda=$request->get('valor_venda');
        $imovel->iptu=$request->get('iptu');
        $imovel->condiminio=$request->get('condiminio');
        $imovel->comissao_adm=$request->get('comissao_adm');
        $imovel->comissao_corretor=$request->get('comissao_corretor');
        $imovel->validade=$request->get('validade');
        $imovel->dt_inicial=$request->get('dt_inicial');
        $imovel->dt_final=$request->get('dt_final');
        $imovel->dt_venda=$request->get('dt_venda');
        $imovel->descricao_imovel=$request->get('descricao_imovel');
        $imovel->img_principal=$request->get('img_principal');
        $imovel->img_banner=$request->get('img_banner');
        $imovel->img_planta=$request->get('img_planta');
        $imovel->url_youtube=$request->get('url_youtube');

    	$imovel->save();
    	return Redirect::to('tabela/imovel');
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

        $imovel=DB::table('imovel as i')
        ->join('proprietario as p', 'i.idproprietario', '=', 'p.idproprietario')
        ->leftjoin('pessoa_dupls as e', 'i.pessoa_dupls_id', '=', 'e.id')
        ->select('i.idimovel','i.idproprietario','p.nome as nomepro','i.idmunicipio','i.nome',
        'i.endereco','i.complemento','i.bairro','i.cidade','i.uf','i.cep','i.referencia','i.obs',
        'i.condicao','i.codigo','i.situacao','e.id as idcor','e.nome as nomecor','i.condicao2',
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
  	return view("tabela.imovel.edit", 
    		["imovel"=>$imovel,
            "proprietarios"=>$proprietarios,
            "corretores"=>$corretores,
            "municipios"=>$municipios]);
    }

    public function update(ImovelFormRequest $request, $id){
    	$imovel=imovel::findOrFail($id);
    	
        $imovel->idproprietario=$request->get('idproprietario');
        $imovel->idmunicipio='1'; //$request->get('idmunicipio');
        $imovel->nome=$request->get('nome');
    	$imovel->endereco=$request->get('endereco');
        $imovel->complemento=$request->get('complemento');
        $imovel->bairro=$request->get('bairro');
        $imovel->cidade=$request->get('cidade');
        $imovel->uf=$request->get('uf');
        $imovel->cep=$request->get('cep');
        $imovel->referencia=$request->get('referencia');
        $imovel->obs=$request->get('obs');
        $imovel->condicao='Ativo';
        $imovel->situacao=$request->get('situacao');
        $imovel->codigo=$request->get('codigo');
        $imovel->pessoa_dupls_id=$request->get('pessoa_dupls_id');
        $imovel->tipo=$request->get('tipo');
        $imovel->status=$request->get('status');
        $imovel->condicao2=$request->get('condicao2');
        $imovel->area=$request->get('area');
        $imovel->area_construida=$request->get('area_construida');
        $imovel->quartos=$request->get('quartos');
        $imovel->banheiros=$request->get('banheiros');
        $imovel->suites=$request->get('suites');
        $imovel->garagens=$request->get('garagens');
        $imovel->piscinas=$request->get('piscinas');
        $imovel->ano_imovel=$request->get('ano_imovel');
        $imovel->valor_locacao=$request->get('valor_locacao');
        $imovel->valor_venda=$request->get('valor_venda');
        $imovel->iptu=$request->get('iptu');
        $imovel->condiminio=$request->get('condiminio');
        $imovel->comissao_adm=$request->get('comissao_adm');
        $imovel->comissao_corretor=$request->get('comissao_corretor');
        $imovel->validade=$request->get('validade');
        $imovel->dt_inicial=$this->parseDate($request->get('dt_inicial')) . ' ' . Carbon::now()->toTimeString(); //$request->get('dt_inicial');
        $imovel->dt_final=$this->parseDate($request->get('dt_final')) . ' ' . Carbon::now()->toTimeString(); //$request->get('dt_final');
        $imovel->dt_venda=$request->get('dt_venda');
        $imovel->descricao_imovel=$request->get('descricao_imovel');
        $imovel->img_principal=$request->get('img_principal');
        $imovel->img_banner=$request->get('img_banner');
        $imovel->img_planta=$request->get('img_planta');
        $imovel->url_youtube=$request->get('url_youtube');
    	$imovel->update();
    	return Redirect::to('tabela/imovel');
    }

    public function destroy($id){
    	$imovel=imovel::findOrFail($id);
    	$imovel->condicao='Inativo';
        $imovel->situacao='Vago_Inativo';
    	$imovel->update();
    	return Redirect::to('tabela/imovel');
    }

    private function parseDate($date, $plusDay = false)
    {
        if ($plusDay == false)
            return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
        else
            return date('Y-m-d', strtotime("+1 day", strtotime(str_replace("/", "-", $date))));
    }

}
