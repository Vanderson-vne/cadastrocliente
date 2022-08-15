<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Fiador;
use App\Inquilino;
use App\Municipio;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\FiadorFormRequest;
use Illuminate\Support\Facades\DB;

class FiadorController extends Controller
{
   public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

        if($request){
    		$query=trim($request->get('searchText'));
    		$fiadores=DB::table('fiador as i')
            ->join('inquilino as in', 'i.idinquilino', '=', 'in.idinquilino')
            ->select('i.idfiador','i.idinquilino','in.nome as nomeinq','i.idmunicipio','i.tipo_pessoa','i.nome','i.fantasia','i.fisica_juridica','i.cpf_cnpj','i.endereco','i.telefone','i.email','i.complemento','i.bairro','i.cidade','i.uf','i.cep','i.referencia','i.obs','i.rg_ie','i.condicao','i.conjuge','i.aos_cuidados','i.end_corr','i.num_corr','i.compl_corr','i.bairro_corr','i.cidade_corr','i.uf_corr','i.cep_corr')
            ->where('i.nome', 'LIKE', '%'.$query.'%')
            ->orwhere('in.nome', 'LIKE', '%'.$query.'%')
            ->orwhere('i.cpf_cnpj', 'LIKE', '%'.$query.'%')
            ->where('i.condicao','=','Ativo')
            ->orderBy('i.idfiador','desc')
    		->get();
    		return view('tabela.fiador.index', [
                "fiador"=>$fiadores,
                "empresas"=>$empresas,
                "searchText"=>$query
    			]);
    	}
    }

    public function create(){

         $inquilinos=DB::table('inquilino')->get();
         $municipios=DB::table('municipio')->get();

    	return view("tabela.fiador.create",[
    		"inquilinos"=>$inquilinos,
    		"municipios"=>$municipios]);
    }

    public function store(FiadorFormRequest $request){

        if($request->conjuge != null){
            $this->validate($request,[
                'cpf_conj'=>'required|cpf',
            ]);
        }

    	$fiador = new fiador;
        $fiador->idinquilino=$request->get('idinquilino');
        $fiador->idmunicipio='1'; //$request->get('idmunicipio');
    	$fiador->tipo_pessoa='Fiador';
        $fiador->nome=$request->get('nome');
        $fiador->fantasia=$request->get('fantasia');
        $fiador->fisica_juridica=$request->get('fisica_juridica');
    	$fiador->cpf_cnpj=$request->get('cpf_cnpj');
    	$fiador->endereco=$request->get('endereco');
    	$fiador->telefone=$request->get('telefone');
    	$fiador->email=$request->get('email');
        $fiador->complemento=$request->get('complemento');
        $fiador->bairro=$request->get('bairro');
        $fiador->cidade=$request->get('cidade');
        $fiador->uf=$request->get('uf');
        $fiador->cep=$request->get('cep');
        $fiador->referencia=$request->get('referencia');
        $fiador->obs=$request->get('obs');
        $fiador->rg_ie=$request->get('rg_ie');
        $fiador->condicao='Ativo';
        $fiador->conjuge=$request->get('conjuge');
        $fiador->aos_cuidados=$request->get('aos_cuidados');
        $fiador->end_corr=$request->get('end_corr');
        $fiador->num_corr=$request->get('num_corr');
        $fiador->compl_corr=$request->get('compl_corr');
        $fiador->bairro_corr=$request->get('bairro_corr');
        $fiador->cidade_corr=$request->get('cidade_corr');
        $fiador->uf_corr=$request->get('uf_corr');
        $fiador->cep_corr=$request->get('cep_corr');
        $fiador->cpf_conj=$request->get('cpf_conj');
        $fiador->rg_conj=$request->get('rg_conj');
    	$fiador->save();
    	return Redirect::to('tabela/fiador');
    }

    public function show($id){
    	return view("tabela.fiador.show",
    		["fiador"=>fiador::findOrFail($id)]);
    }


 	public function edit($id){

         $inquilinos=DB::table('inquilino')->get();
         $municipios=DB::table('municipio')->get();

         $fiadores=DB::table('fiador as i')
        ->join('inquilino as in', 'i.idinquilino', '=', 'in.idinquilino')
        ->select('i.idfiador','i.idinquilino','in.nome as nomeinq','i.idmunicipio','i.tipo_pessoa','i.nome','i.fantasia','i.fisica_juridica','i.cpf_cnpj','i.endereco','i.telefone','i.email','i.complemento','i.bairro','i.cidade','i.uf','i.cep','i.referencia','i.obs','i.rg_ie','i.condicao','i.conjuge','i.aos_cuidados','i.end_corr','i.num_corr','i.compl_corr','i.bairro_corr','i.cidade_corr','i.uf_corr','i.cep_corr')
         ->where('i.idfiador','=',$id)
        ->get();

    	return view("tabela.fiador.edit",
    		["fiador"=>fiador::findOrFail($id),
    		"inquilinos"=>$inquilinos,
            "fiadores"=>$fiadores,
            "municipios"=>$municipios
        ]);
    }


    public function update(FiadorFormRequest $request, $id){
    	$fiador=fiador::findOrFail($id);
        $fiador->idinquilino=$request->get('idinquilino');
        $fiador->idmunicipio='1'; //$request->get('idmunicipio');
    	$fiador->tipo_pessoa='Fiador';
        $fiador->nome=$request->get('nome');
        $fiador->fantasia=$request->get('fantasia');
        $fiador->fisica_juridica=$request->get('fisica_juridica');
    	$fiador->cpf_cnpj=$request->get('cpf_cnpj');
    	$fiador->endereco=$request->get('endereco');
    	$fiador->telefone=$request->get('telefone');
    	$fiador->email=$request->get('email');
        $fiador->complemento=$request->get('complemento');
        $fiador->bairro=$request->get('bairro');
        $fiador->cidade=$request->get('cidade');
        $fiador->uf=$request->get('uf');
        $fiador->cep=$request->get('cep');
        $fiador->referencia=$request->get('referencia');
        $fiador->obs=$request->get('obs');
        $fiador->rg_ie=$request->get('rg_ie');
        $fiador->condicao='Ativo';
        $fiador->conjuge=$request->get('conjuge');
        $fiador->aos_cuidados=$request->get('aos_cuidados');
        $fiador->end_corr=$request->get('end_corr');
        $fiador->num_corr=$request->get('num_corr');
        $fiador->compl_corr=$request->get('compl_corr');
        $fiador->bairro_corr=$request->get('bairro_corr');
        $fiador->cidade_corr=$request->get('cidade_corr');
        $fiador->uf_corr=$request->get('uf_corr');
        $fiador->cep_corr=$request->get('cep_corr');
        $fiador->cpf_conj=$request->get('cpf_conj');
        $fiador->rg_conj=$request->get('rg_conj');
    	$fiador->update();
    	return Redirect::to('tabela/fiador');
    }

    public function destroy($id){
    	$fiador=fiador::findOrFail($id);
    	$fiador->condicao='Inativo';
    	$fiador->update();
    	return Redirect::to('tabela/fiador');
    }
}
