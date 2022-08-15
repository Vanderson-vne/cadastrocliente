<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Empresa;
use App\PessoaDupl;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\PessoaDuplFormRequest;
use Illuminate\Support\Facades\DB;

class CorretorController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

        if($request){
    		$query=trim($request->get('searchText'));
    		$corretores=DB::table('pessoa_dupls as i')
            ->where('i.nome', 'LIKE', '%'.$query.'%')
            ->where('i.for_cli','=','Corretor')
            ->where('i.condicao','=','Ativo')
            ->orderBy('i.id','desc')
    		->get();
    		return view('financeiro.corretor.index', [
                "corretores"=>$corretores,
                "empresas"=>$empresas,
                "searchText"=>$query
    			]);
    	}
    }

    public function create(){


    	return view("financeiro.corretor.create",[
    		]);
    }

    public function store(PessoaDuplFormRequest $request){

        if($request->conjuge != null){
            $this->validate($request,[
                'cpf_conj'=>'required|cpf',
            ]);
        }

    	$corretor = new PessoaDupl;
        $corretor->nome=$request->get('nome');
        $corretor->fantasia=$request->get('fantasia');
        $corretor->fisica_juridica=$request->get('fisica_juridica');
    	$corretor->cpf_cnpj=$request->get('cpf_cnpj');
    	$corretor->endereco=$request->get('endereco');
    	$corretor->telefone=$request->get('telefone');
    	$corretor->email=$request->get('email');
        $corretor->complemento=$request->get('complemento');
        $corretor->bairro=$request->get('bairro');
        $corretor->cidade=$request->get('cidade');
        $corretor->uf=$request->get('uf');
        $corretor->cep=$request->get('cep');
        $corretor->referencia=$request->get('referencia');
        $corretor->obs=$request->get('obs');
        $corretor->rg_ie=$request->get('rg_ie');
        $corretor->conjuge=$request->get('conjuge');
        $corretor->aos_cuidados=$request->get('aos_cuidados');
        $corretor->end_corr=$request->get('end_corr');
        $corretor->num_corr=$request->get('num_corr');
        $corretor->compl_corr=$request->get('compl_corr');
        $corretor->bairro_corr=$request->get('bairro_corr');
        $corretor->cidade_corr=$request->get('cidade_corr');
        $corretor->uf_corr=$request->get('uf_corr');
        $corretor->cep_corr=$request->get('cep_corr');
        $corretor->cpf_conj=$request->get('cpf_conj');
        $corretor->rg_conj=$request->get('rg_conj');
        $corretor->for_cli='Corretor'; 
        $corretor->condicao='Ativo';
    	$corretor->save();
    	return Redirect::to('financeiro/corretor');
    }

    public function show($id){
    	return view("financeiro.corretor.show",
    		["corretor"=>PessoaDupl::findOrFail($id)]);
    }


 	public function edit($id){

    	return view("financeiro.corretor.edit",
    		["corretor"=>PessoaDupl::findOrFail($id)]);
    }


    public function update(PessoaDuplFormRequest $request, $id){
        $corretor=PessoaDupl::findOrFail($id);
        $corretor->nome=$request->get('nome');
        $corretor->fantasia=$request->get('fantasia');
        $corretor->fisica_juridica=$request->get('fisica_juridica');
    	$corretor->cpf_cnpj=$request->get('cpf_cnpj');
    	$corretor->endereco=$request->get('endereco');
    	$corretor->telefone=$request->get('telefone');
    	$corretor->email=$request->get('email');
        $corretor->complemento=$request->get('complemento');
        $corretor->bairro=$request->get('bairro');
        $corretor->cidade=$request->get('cidade');
        $corretor->uf=$request->get('uf');
        $corretor->cep=$request->get('cep');
        $corretor->referencia=$request->get('referencia');
        $corretor->obs=$request->get('obs');
        $corretor->rg_ie=$request->get('rg_ie');
        $corretor->conjuge=$request->get('conjuge');
        $corretor->aos_cuidados=$request->get('aos_cuidados');
        $corretor->end_corr=$request->get('end_corr');
        $corretor->num_corr=$request->get('num_corr');
        $corretor->compl_corr=$request->get('compl_corr');
        $corretor->bairro_corr=$request->get('bairro_corr');
        $corretor->cidade_corr=$request->get('cidade_corr');
        $corretor->uf_corr=$request->get('uf_corr');
        $corretor->cep_corr=$request->get('cep_corr');
        $corretor->cpf_conj=$request->get('cpf_conj');
        $corretor->rg_conj=$request->get('rg_conj');
        $corretor->for_cli='Corretor'; 
        $corretor->condicao='Ativo';
    	$corretor->update();
    	return Redirect::to('financeiro/corretor');
    }

    public function destroy($id){
    	$corretor=PessoaDupl::findOrFail($id);
    	$corretor->condicao='Inativo';
    	$corretor->update();
    	return Redirect::to('financeiro/corretor');
    }
}
