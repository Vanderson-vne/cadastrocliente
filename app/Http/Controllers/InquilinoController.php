<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Inquilino;
use App\Proprietario;
use App\Imovel;
use App\Municipio;
use App\User;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\InquilinoFormRequest;
use Illuminate\Support\Facades\DB;

class InquilinoController extends Controller
{
   public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

         $empresas=DB::table('empresa as emp')
        ->get();

    	if($request){
    		$ativo='Ativo';
            $query=trim($request->get('searchText'));
    		$inquilinos=DB::table('inquilino as i')
            ->join('proprietario as p', 'i.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'i.idimovel', '=', 'im.idimovel')
        	->select('i.idinquilino','i.idproprietario','i.idlocacao','p.nome as nomepro','i.idimovel','i.idmunicipio','i.tipo_pessoa',
            'i.nome','i.fantasia','i.fisica_juridica','i.cpf_cnpj','i.endereco','i.telefone','i.email','i.complemento','i.bairro',
            'i.cidade','i.uf','i.cep','i.referencia','i.obs','i.rg_ie','i.condicao','i.conjuge','i.aos_cuidados','i.end_corr',
            'i.num_corr','i.compl_corr','i.bairro_corr','i.cidade_corr','i.uf_corr','i.cep_corr','i.favorecido','i.cpf_fav',
            'i.banco_fav','i.ag_fav','i.conta_fav','i.ult_extrato','i.data_ult_extrato','i.irrf','i.locacao_encerada','i.dt_enc_locacao','i.ult_recibo','im.endereco as endimovel')
            ->where('i.condicao','LIKE','Ativo')
            ->where('i.nome', 'LIKE', '%'.$query.'%')
            ->orwhere('i.cpf_cnpj', 'LIKE', '%'.$query.'%')
            ->orwhere('im.endereco', 'LIKE', '%'.$query.'%')
            ->orwhere('p.nome', 'LIKE', '%'.$query.'%')
            ->orderBy('i.idinquilino','desc')
    		->get();

            //dd($inquilinos);
    		return view('tabela.inquilino.index', [
                "inquilinos"=>$inquilinos,
                "empresas"=>$empresas,
                "searchText"=>$query
    			]);
    	}
    }

    public function create(){

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->join('proprietario as p', 'im.idproprietario', '=', 'p.idproprietario')
        ->select('im.idimovel','im.endereco','p.nome as nomepro')
        ->where('im.condicao','=','Ativo')
        ->where('im.situacao','=','Vago')
        ->get();

        $inquilinos=DB::table('inquilino')->get();
        $municipios=DB::table('municipio')->get();

    	return view("tabela.inquilino.create",[
    		"proprietarios"=>$proprietarios,
    		"imoveis"=>$imoveis,
    		"municipios"=>$municipios]);
    }

    public function store(InquilinoFormRequest $request){

        if($request->favorecido != null){
            $this->validate($request,[
                'cpf_fav'=>'required|cpf',
            ]);
        }

        $imoveis=DB::table('imovel as im')
        ->join('proprietario as p', 'im.idproprietario', '=', 'p.idproprietario')
        ->select('im.idimovel','im.endereco','p.idproprietario','p.nome as nomepro')
        ->where('im.idimovel','=',$request->idimovel)
        ->get();

        $user = User::where('email',$request->email)->first();

        if(!$user){
            $user = new User;

            $user->name = $request->nome;
            $user->email = $request->email;
            $user->password = bcrypt('12345678');
            $user->status = 1;

            $user->save();
        }

        $user->assignRole('Inquilino');

        $inquilino = new Inquilino;

        $inquilino->idproprietario = $imoveis[0]->idproprietario;
        $inquilino->idimovel = $imoveis[0]->idimovel;
        $inquilino->idmunicipio = '1'; //$request->get('idmunicipio');
        $inquilino->tipo_pessoa = 'Inquilino';
        $inquilino->condicao = 'Ativo';
        $inquilino->nome = $request->get('nome');
        $inquilino->fantasia = $request->get('fantasia');
        $inquilino->fisica_juridica = $request->get('fisica_juridica');
        $inquilino->cpf_cnpj = $request->get('cpf_cnpj');
        $inquilino->endereco = $request->get('endereco');
        $inquilino->telefone = $request->get('telefone');
        $inquilino->email = $request->get('email');
        $inquilino->complemento = $request->get('complemento');
        $inquilino->bairro = $request->get('bairro');
        $inquilino->cidade = $request->get('cidade');
        $inquilino->uf = $request->get('uf');
        $inquilino->cep = $request->get('cep');
        $inquilino->referencia = $request->get('referencia');
        $inquilino->obs = $request->get('obs');
        $inquilino->rg_ie = $request->get('rg_ie');
        $inquilino->conjuge = $request->get('conjuge');
        $inquilino->aos_cuidados = $request->get('aos_cuidados');
        $inquilino->end_corr = $request->get('end_corr');
        $inquilino->num_corr = $request->get('num_corr');
        $inquilino->compl_corr = $request->get('compl_corr');
        $inquilino->bairro_corr = $request->get('bairro_corr');
        $inquilino->cidade_corr = $request->get('cidade_corr');
        $inquilino->uf_corr = $request->get('uf_corr');
        $inquilino->cep_corr = $request->get('cep_corr');
        $inquilino->favorecido = $request->get('favorecido');
        $inquilino->cpf_fav = $request->get('cpf_fav');
        $inquilino->banco_fav = $request->get('banco_fav');
        $inquilino->ag_fav = $request->get('ag_fav');
        $inquilino->conta_fav = $request->get('conta_fav');
        $inquilino->ult_extrato = $request->get('ult_extrato');
        $inquilino->data_ult_extrato = $request->get('data_ult_extrato');
        $inquilino->irrf = $request->get('irrf');
        $inquilino->locacao_encerada = $request->get('locacao_enceda');
        $inquilino->ult_recibo = $request->get('ult_recibo');
        $inquilino->user_id = $user->id;

        $inquilino->save();
        $regimovel=$inquilino->idimovel;

        $imovel_up = DB::table('imovel')
            ->where('idimovel', $regimovel)
            ->update([
                'situacao' => 'Alugado',
                'idinquilino'=> $inquilino->idinquilino
        ]);

        return Redirect::to('tabela/inquilino');
    }

    public function show($id){
        return view("tabela.inquilino.show",
            ["inquilino"=>inquilino::findOrFail($id)]);
    }


    public function edit($id){

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->where('im.condicao','=','Ativo')
        ->where('im.situacao','=','Vago')
        ->get();

        $municipios=DB::table('municipio')->get();

        $inquilinos=DB::table('inquilino as i')
        ->join('proprietario as p', 'i.idproprietario', '=', 'p.idproprietario')
        ->join('imovel as im', 'i.idimovel', '=', 'im.idimovel')
        ->select('i.idinquilino','i.idproprietario','p.nome as nomepro','i.idimovel','i.idmunicipio','i.tipo_pessoa','i.nome','i.fantasia','i.fisica_juridica','i.cpf_cnpj','i.endereco as endinq','i.telefone','i.email','i.complemento','i.bairro','i.cidade','i.uf','i.cep','i.referencia','i.obs','i.rg_ie','i.condicao','i.conjuge','i.aos_cuidados','i.end_corr','i.num_corr','i.compl_corr','i.bairro_corr','i.cidade_corr','i.uf_corr','i.cep_corr','i.favorecido','i.cpf_fav','i.banco_fav','i.ag_fav','i.conta_fav','i.ult_extrato','i.data_ult_extrato','i.irrf','i.locacao_encerada','i.dt_enc_locacao','i.ult_recibo','im.endereco as endimovel')
        ->where('i.idinquilino','=',$id )
        ->get();

        return view("tabela.inquilino.edit",
            ["inquilino"=>inquilino::findOrFail($id),
            "proprietarios"=>$proprietarios,
            "imoveis"=>$imoveis,
            "inquilinos"=>$inquilinos,
            "municipios"=>$municipios]);
    }


    public function update(InquilinoFormRequest $request, $id){

        $inquilino = Inquilino::findOrFail($id);

        $inquilino->idproprietario = $request->get('idproprietario');
        $inquilino->idimovel = $request->get('idimovel');
        $inquilino->idmunicipio = '1'; //$request->get('idmunicipio');
        $inquilino->tipo_pessoa = 'Inquilino';
        $inquilino->condicao = 'Ativo';
        $inquilino->nome = $request->get('nome');
        $inquilino->fantasia = $request->get('fantasia');
        $inquilino->fisica_juridica = $request->get('fisica_juridica');
        $inquilino->cpf_cnpj = $request->get('cpf_cnpj');
        $inquilino->endereco = $request->get('endereco');
        $inquilino->telefone = $request->get('telefone');
        $inquilino->email = $request->get('email');
        $inquilino->complemento = $request->get('complemento');
        $inquilino->bairro = $request->get('bairro');
        $inquilino->cidade = $request->get('cidade');
        $inquilino->uf = $request->get('uf');
        $inquilino->cep = $request->get('cep');
        $inquilino->referencia = $request->get('referencia');
        $inquilino->obs = $request->get('obs');
        $inquilino->rg_ie = $request->get('rg_ie');
        $inquilino->conjuge = $request->get('conjuge');
        $inquilino->aos_cuidados = $request->get('aos_cuidados');
        $inquilino->end_corr = $request->get('end_corr');
        $inquilino->num_corr = $request->get('num_corr');
        $inquilino->compl_corr = $request->get('compl_corr');
        $inquilino->bairro_corr = $request->get('bairro_corr');
        $inquilino->cidade_corr = $request->get('cidade_corr');
        $inquilino->uf_corr = $request->get('uf_corr');
        $inquilino->cep_corr = $request->get('cep_corr');
        $inquilino->favorecido = $request->get('favorecido');
        $inquilino->cpf_fav = $request->get('cpf_fav');
        $inquilino->banco_fav = $request->get('banco_fav');
        $inquilino->ag_fav = $request->get('ag_fav');
        $inquilino->conta_fav = $request->get('conta_fav');
        $inquilino->ult_extrato = $request->get('ult_extrato');
        $inquilino->data_ult_extrato = $request->get('data_ult_extrato');
        $inquilino->irrf = $request->get('irrf');
        $inquilino->locacao_encerada = $request->get('locacao_enceda');
        $inquilino->ult_recibo = $request->get('ult_recibo');

        $inquilino->update();

        return Redirect::to('tabela/inquilino');
    }

    public function destroy($id){
    	$inquilino=inquilino::findOrFail($id);
        $inquilino->condicao='Inativo';
        $inquilino->idlocacao='';
    	$inquilino->update();
    	return Redirect::to('tabela/inquilino');
    }


}
