<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Empresa;
use App\PessoaDupl;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\PessoaDuplFormRequest;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

        if($request){
    		$query=trim($request->get('searchText'));
    		$fornecedores=DB::table('pessoa_dupls as i')
            ->where('i.nome', 'LIKE', '%'.$query.'%')
            ->where('i.for_cli','=','Cliente')
            ->where('i.condicao','=','Ativo')
            ->orderBy('i.id','desc')
    		->get();
    		return view('financeiro.cliente.index', [
                "fornecedor"=>$fornecedores,
                "empresas"=>$empresas,
                "searchText"=>$query
    			]);
    	}
    }

    public function create(){


    	return view("financeiro.cliente.create",[
    		]);
    }

    public function store(PessoaDuplFormRequest $request){

        if($request->conjuge != null){
            $this->validate($request,[
                'cpf_conj'=>'required|cpf',
            ]);
        }

    	$fornecedor = new PessoaDupl;
        $fornecedor->nome=$request->get('nome');
        $fornecedor->fantasia=$request->get('fantasia');
        $fornecedor->fisica_juridica=$request->get('fisica_juridica');
    	$fornecedor->cpf_cnpj=$request->get('cpf_cnpj');
    	$fornecedor->endereco=$request->get('endereco');
    	$fornecedor->telefone=$request->get('telefone');
    	$fornecedor->email=$request->get('email');
        $fornecedor->complemento=$request->get('complemento');
        $fornecedor->bairro=$request->get('bairro');
        $fornecedor->cidade=$request->get('cidade');
        $fornecedor->uf=$request->get('uf');
        $fornecedor->cep=$request->get('cep');
        $fornecedor->referencia=$request->get('referencia');
        $fornecedor->obs=$request->get('obs');
        $fornecedor->rg_ie=$request->get('rg_ie');
        $fornecedor->conjuge=$request->get('conjuge');
        $fornecedor->aos_cuidados=$request->get('aos_cuidados');
        $fornecedor->end_corr=$request->get('end_corr');
        $fornecedor->num_corr=$request->get('num_corr');
        $fornecedor->compl_corr=$request->get('compl_corr');
        $fornecedor->bairro_corr=$request->get('bairro_corr');
        $fornecedor->cidade_corr=$request->get('cidade_corr');
        $fornecedor->uf_corr=$request->get('uf_corr');
        $fornecedor->cep_corr=$request->get('cep_corr');
        $fornecedor->cpf_conj=$request->get('cpf_conj');
        $fornecedor->rg_conj=$request->get('rg_conj');
        $fornecedor->for_cli='Cliente'; 
        $fornecedor->condicao='Ativo';
    	$fornecedor->save();
    	return Redirect::to('financeiro/cliente');
    }

    public function show($id){
    	return view("financeiro.cliente.show",
    		["fornecedor"=>PessoaDupl::findOrFail($id)]);
    }


 	public function edit($id){

    	return view("financeiro.cliente.edit",
    		["fornecedor"=>PessoaDupl::findOrFail($id)]);
    }


    public function update(PessoaDuplFormRequest $request, $id){
        $fornecedor=PessoaDupl::findOrFail($id);
        $fornecedor->nome=$request->get('nome');
        $fornecedor->fantasia=$request->get('fantasia');
        $fornecedor->fisica_juridica=$request->get('fisica_juridica');
    	$fornecedor->cpf_cnpj=$request->get('cpf_cnpj');
    	$fornecedor->endereco=$request->get('endereco');
    	$fornecedor->telefone=$request->get('telefone');
    	$fornecedor->email=$request->get('email');
        $fornecedor->complemento=$request->get('complemento');
        $fornecedor->bairro=$request->get('bairro');
        $fornecedor->cidade=$request->get('cidade');
        $fornecedor->uf=$request->get('uf');
        $fornecedor->cep=$request->get('cep');
        $fornecedor->referencia=$request->get('referencia');
        $fornecedor->obs=$request->get('obs');
        $fornecedor->rg_ie=$request->get('rg_ie');
        $fornecedor->conjuge=$request->get('conjuge');
        $fornecedor->aos_cuidados=$request->get('aos_cuidados');
        $fornecedor->end_corr=$request->get('end_corr');
        $fornecedor->num_corr=$request->get('num_corr');
        $fornecedor->compl_corr=$request->get('compl_corr');
        $fornecedor->bairro_corr=$request->get('bairro_corr');
        $fornecedor->cidade_corr=$request->get('cidade_corr');
        $fornecedor->uf_corr=$request->get('uf_corr');
        $fornecedor->cep_corr=$request->get('cep_corr');
        $fornecedor->cpf_conj=$request->get('cpf_conj');
        $fornecedor->rg_conj=$request->get('rg_conj');
        $fornecedor->for_cli='Cliente'; 
        $fornecedor->condicao='Ativo';
    	$fornecedor->update();
    	return Redirect::to('financeiro/cliente');
    }

    public function destroy($id){
    	$fornecedor=PessoaDupl::findOrFail($id);
    	$fornecedor->condicao='Inativo';
    	$fornecedor->update();
    	return Redirect::to('financeiro/cliente');
    }
}
