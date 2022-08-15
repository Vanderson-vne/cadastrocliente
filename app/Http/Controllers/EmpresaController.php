<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\EmpresaFormRequest;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request)
        {
            $query = trim($request->get('searchText'));

            $empresas = DB::table('empresa')
            ->where('nome', 'LIKE', '%' . $query . '%')
            ->orderBy('idempresa', 'desc')
            ->get();

            return view('tabela.empresa.index', [
                "empresas" => $empresas, "searchText" => $query
            ]);
        }
    }

    public function create()
    {
        return view("tabela.empresa.create");
    }

    public function store(EmpresaFormRequest $request)
    {
        $empresa = new Empresa;

        $empresa->nome = $request->get('nome');
        $empresa->fantasia = $request->get('fantasia');
        $empresa->endereco = $request->get('endereco');
        $empresa->bairro = $request->get('bairro');
        $empresa->cidade = $request->get('cidade');
        $empresa->estado = $request->get('estado');
        $empresa->cep = $request->get('cep');
        $empresa->cnpj = $request->get('cnpj');
        $empresa->responsavel = $request->get('responsavel');
        $empresa->cpf = $request->get('cpf');
        $empresa->creci = $request->get('creci');
        $empresa->email = $request->get('email');
        $empresa->banco_padrao_boleto = $request->get('banco_padrao_boleto');
        $empresa->telefone = $request->get('telefone');
        $empresa->gera_todos_boletos = $request->get('gera_todos_boletos');
        $empresa->conta_caixa = $request->get('conta_caixa');
        $empresa->transacao_caixa = $request->get('transacao_caixa');
        $empresa->save();

        return Redirect::to('tabela/empresa');
    }

    public function show($id)
    {
        return view(
            "tabela.empresa.show",[
                "empresa" => empresa::findOrFail($id)
            ]
        );
    }


    public function edit($id)
    {
        return view(
            "tabela.empresa.edit",[
                "empresa" => empresa::findOrFail($id)
            ]
        );
    }


    public function update(EmpresaFormRequest $request, $id)
    {

        $boletos = $request->get('gera_todos_boletos');
        if ($boletos == "") {
            dd('Opção de gera_todos_boletos esta em branco ..!');
        }

        $empresa = Empresa::findOrFail($id);

        $empresa->nome = $request->get('nome');
        $empresa->fantasia = $request->get('fantasia');
        $empresa->endereco = $request->get('endereco');
        $empresa->bairro = $request->get('bairro');
        $empresa->cidade = $request->get('cidade');
        $empresa->estado = $request->get('estado');
        $empresa->cep = $request->get('cep');
        $empresa->cnpj = $request->get('cnpj');
        $empresa->responsavel = $request->get('responsavel');
        $empresa->cpf = $request->get('cpf');
        $empresa->creci = $request->get('creci');
        $empresa->email = $request->get('email');
        $empresa->banco_padrao_boleto = $request->get('banco_padrao_boleto');
        $empresa->telefone = $request->get('telefone');
        $empresa->gera_todos_boletos = $request->get('gera_todos_boletos');
        $empresa->conta_caixa = $request->get('conta_caixa');
        $empresa->transacao_caixa = $request->get('transacao_caixa');
        $empresa->logo_url = $request->get('logo_url');
        $empresa->update();

        return Redirect::to('tabela/empresa');
    }

    public function destroy($id)
    {
        $empresa = empresa::findOrFail($id);
        $empresa->delete();

        return Redirect::to('tabela/empresa');
    }
}
