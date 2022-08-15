<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Proprietario;
use App\User;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProprietarioFormRequest;
use Illuminate\Support\Facades\DB;


class ProprietarioController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

    	if($request){
    		$query=trim($request->get('searchText'));
    		$proprietarios=DB::table('proprietario')
    		->where('nome', 'LIKE', '%'.$query.'%')
    		->orwhere('idproprietario', 'LIKE', '%'.$query.'%')
            ->orwhere('cpf_cnpj', 'LIKE', '%'.$query.'%')
            //->where('condicao','=','Ativo')
    		->where('tipo_pessoa', '=', 'Proprietario')
            ->orderBy('idproprietario','desc')
    		->get();
    		return view('tabela.proprietario.index', [
                "proprietario"=>$proprietarios,
                "empresas"=>$empresas,
                "searchText"=>$query
    			]);
    	}
    }

    public function create(){

        $municipios=DB::table('municipio')->get();

        return view("tabela.proprietario.create",["municipios"=>$municipios]);
    }

    public function store(ProprietarioFormRequest $request)
    {
        if($request->favorecido){
            $this->validate($request,[
                'cpf_fav'=>'required|cpf',
            ]);
        }

        $user = User::where('email',$request->email)->first();

        if(!$user){
            $user = new User;

            $user->name = $request->nome;
            $user->email = $request->email;
            $user->password = bcrypt('12345678');
            $user->status = 1;

            $user->save();
        }

        $user->assignRole('Proprietario');

        $proprietario = new Proprietario;
        $proprietario->idmunicipio = '1'; //$request->get('idmunicipio');
        $proprietario->tipo_pessoa = 'Proprietario';
        $proprietario->condicao = 'Ativo';
        $proprietario->nome = $request->get('nome');
        $proprietario->fantasia = $request->get('fantasia');
        $proprietario->fisica_juridica = $request->get('fisica_juridica');
        $proprietario->cpf_cnpj = $request->get('cpf_cnpj');
        $proprietario->endereco = $request->get('endereco');
        $proprietario->telefone = $request->get('telefone');
        $proprietario->email = $request->get('email');
        $proprietario->complemento_end = $request->get('complemento_end');
        $proprietario->bairro = $request->get('bairro');
        $proprietario->cidade = $request->get('cidade');
        $proprietario->uf = $request->get('uf');
        $proprietario->cep = $request->get('cep');
        $proprietario->referencia = $request->get('referencia');
        $proprietario->obs_prop = $request->get('obs_prop');
        $proprietario->rg_ie = $request->get('rg_ie');
        $proprietario->conjuge = $request->get('conjuge');
        $proprietario->aos_cuidados = $request->get('aos_cuidados');
        $proprietario->end_corr = $request->get('end_corr');
        $proprietario->num_corr = $request->get('num_corr');
        $proprietario->compl_corr = $request->get('compl_corr');
        $proprietario->bairro_corr = $request->get('bairro_corr');
        $proprietario->cidade_corr = $request->get('cidade_corr');
        $proprietario->uf_corr = $request->get('uf_corr');
        $proprietario->cep_corr = $request->get('cep_corr');
        $proprietario->favorecido = $request->get('favorecido');
        $proprietario->cpf_fav = $request->get('cpf_fav');
        $proprietario->banco_fav = $request->get('banco_fav');
        $proprietario->ag_fav = $request->get('ag_fav');
        $proprietario->conta_fav = $request->get('conta_fav');
        $proprietario->estado_civil = $request->get('estado_civil');
        $proprietario->user_id = $user->id;

        $proprietario->save();

        return Redirect::to('tabela/proprietario');
    }

    public function show($id){
    	return view("tabela.proprietario.show",
    		["proprietario"=>proprietario::findOrFail($id)]);
    }

    public function edit($id){

         $municipios=DB::table('municipio')->get();

    	return view("tabela.proprietario.edit",
    		["proprietario"=>proprietario::findOrFail($id),
            "municipios"=>$municipios]);
    }

    public function update(ProprietarioFormRequest $request, $id){

        $proprietario = proprietario::findOrFail($id);

        $proprietario->idmunicipio = '1'; //$request->get('idmunicipio');
        $proprietario->tipo_pessoa = 'Proprietario';
        $proprietario->condicao = 'Ativo';
        $proprietario->nome = $request->get('nome');
        $proprietario->fantasia = $request->get('fantasia');
        $proprietario->fisica_juridica = $request->get('fisica_juridica');
        $proprietario->cpf_cnpj = $request->get('cpf_cnpj');
        $proprietario->endereco = $request->get('endereco');
        $proprietario->telefone = $request->get('telefone');
        $proprietario->email = $request->get('email');
        $proprietario->complemento_end = $request->get('complemento_end');
        $proprietario->bairro = $request->get('bairro');
        $proprietario->cidade = $request->get('cidade');
        $proprietario->uf = $request->get('uf');
        $proprietario->cep = $request->get('cep');
        $proprietario->referencia = $request->get('referencia');
        $proprietario->obs_prop = $request->get('obs_prop');
        $proprietario->rg_ie = $request->get('rg_ie');
        $proprietario->conjuge = $request->get('conjuge');
        $proprietario->aos_cuidados = $request->get('aos_cuidados');
        $proprietario->end_corr = $request->get('end_corr');
        $proprietario->num_corr = $request->get('num_corr');
        $proprietario->compl_corr = $request->get('compl_corr');
        $proprietario->bairro_corr = $request->get('bairro_corr');
        $proprietario->cidade_corr = $request->get('cidade_corr');
        $proprietario->uf_corr = $request->get('uf_corr');
        $proprietario->cep_corr = $request->get('cep_corr');
        $proprietario->favorecido = $request->get('favorecido');
        $proprietario->cpf_fav = $request->get('cpf_fav');
        $proprietario->banco_fav = $request->get('banco_fav');
        $proprietario->ag_fav = $request->get('ag_fav');
        $proprietario->conta_fav = $request->get('conta_fav');
        $proprietario->estado_civil = $request->get('estado_civil');

        $proprietario->update();

        return Redirect::to('tabela/proprietario');
    }

    public function destroy($id){
    	$proprietario=proprietario::findOrFail($id);
    	$proprietario->condicao='Inativo';
    	$proprietario->update();
    	return Redirect::to('tabela/proprietario');
    }
}
