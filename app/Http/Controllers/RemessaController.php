<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Recibo;
use App\DetalheRecibo;
use App\Remessa;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ReciboFormRequest;
use Illuminate\Support\Facades\DB;


use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class RemessaController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        if($request->get('searchText') == ''){
            $date = Carbon::now();
            $date = $date->format('Y-m-d');
        }else{
            $date=trim($request->get('searchText'));
            //dd($date); die();
        }

        $remessas = DB::table('remessas as rem')
        ->whereBetween('created_at', [$date.' 00:00:01', $date.' 23:59:59'])
        ->get();

        //dd($remessas, $date); die();
        $datacarbon = '';

		$empresas=DB::table('empresa as emp')
        ->get();

        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->where('im.condicao','=','Ativo')->get();

        $indices=DB::table('indice as ind')->get();

        $recibos = DB::table('recibo as r')
    		->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'l.idindice', '=', 'in.idindice')
            ->join('remessas as rem', 'r.idremessa', '=', 'rem.id')
    		->select('rem.id','r.idrecibo','r.mes_ano','l.idlocacao', 'i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco', 'in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.nomeremessa')
            ->whereBetween('rem.created_at', [$date.' 00:00:01', $date.' 23:59:59'])
            ->orderBy('r.idrecibo','desc')
            ->get();

        //dd($recibos);die();

        return view('banco.remessa.index', [
            "recibos"=>$recibos,
            "empresas"=>$empresas,
            "data"=>$date,
            "remessas"=>$remessas,
            "datacarbon"=>$datacarbon
            ]);
    }

    public function edit($id){

        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->where('im.condicao','=','Ativo')->get();

        $indices=DB::table('indice as ind')->get();

        $eventos=DB::table('evento as eve')->get();

        $locacoes=DB::table('locacao as l')
        ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
        ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
        ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
        ->join('indice as in', 'l.idindice', '=', 'in.idindice')
        ->select('l.idlocacao', 'i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco', 'in.nome as nomeind','l.dt_inicial','l.dt_final','l.reajuste', 'l.contador_aluguel','l.reajuste_sobre', 'l.vencimento')
        ->where('l.estado','=','Ativo')
        ->get();

        $detalhes=DB::table('detalhe_recibo as d')
        ->join('evento as e','d.idevento','=','e.idevento')
        ->select('e.nome as evento', 'd.complemento', 'd.qtde', 'd.valor', 'd.mes_ano', 'd.qtde_limite')
        ->where('d.idrecibo','=', $id)
        ->get();

    	return view("tabela/recibo.edit",
            ["recibo"=>Recibo::findOrFail($id),
            "inquilinos"=>$inquilinos,
            "proprietarios"=>$proprietarios,
            "imoveis"=>$imoveis,
            "eventos"=>$eventos,
            "indices"=>$indices,
            "locacoes"=>$locacoes,
            "detalhes"=>$detalhes]);
    }

    public function all(){

        $date = Carbon::now();
        $date = $date->format('Y-m-d');

        $remessas = DB::table('remessas as rem')
        ->get();

		$empresas=DB::table('empresa as emp')
        ->get();

        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->where('im.condicao','=','Ativo')->get();

        $indices=DB::table('indice as ind')->get();

        $recibos = DB::table('recibo as r')
    		->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'l.idindice', '=', 'in.idindice')
            ->join('remessas as rem', 'r.idremessa', '=', 'rem.id')
    		->select('rem.id','r.idrecibo','r.mes_ano','l.idlocacao', 'i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco', 'in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.nomeremessa')
            ->orderBy('r.idrecibo','desc')
            ->get();

        //dd($recibos);die();

        return view('banco.remessa.index', [
            "recibos"=>$recibos,
            "empresas"=>$empresas,
            "remessas"=>$remessas,
            "data"=>$date
            ]);
    }

    public function print(Request $request){

        $empresa=DB::table('empresa as emp')->get();

        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();

        $banco=DB::table('banco as ban')
        ->join('empresa as e', 'ban.codigo', '=', 'e.banco_padrao_boleto')
        ->get();

        $indices=DB::table('indice as ind')->get();

        $recibos = DB::table('recibo as r')
        ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
        ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
        ->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
        ->select('r.idrecibo','l.reajuste_sobre as valor_boleto','r.idrecibo as numero_documento','r.mes_ano','l.idlocacao', 'i.nome as cliente','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','r.dt_vencimento as data_vencimento','i.nome as sacado','i.endereco as endereco1','i.cidade as endereco2')
        ->get();

        $id = $recibos[0]->idrecibo;
        $detalhes=DB::table('detalhe_recibo as d')
        ->join('evento as e','d.idevento','=','e.idevento')
        ->select('e.nome as evento', 'd.complemento', 'd.qtde', 'd.valor', 'd.mes_ano', 'd.qtde_limite')
        ->where('d.idrecibo','=', $id)
        ->get();

        // DADOS DO BOLETO PARA O SEU CLIENTE
        //Variaveis do boleto_sicredi

        $dias_de_prazo_para_pagamento = 5;
        $taxa_boleto = 0; //$banco[0]->juros; //2.95;
        //$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
        $data_venc = \Carbon\Carbon::parse($recibos[0]->data_vencimento)->format('d/m/Y');  // Prazo de X dias OU informe data: "13/04/2006";
        $valor_cobrado = $recibos[0]->valor_boleto; //"2950,00"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
        $valor_cobrado = str_replace(",", ".",$valor_cobrado);
        $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

        $dadosboleto["inicio_nosso_numero"] = date("y");    // Ano da geração do título ex: 07 para 2007
        $dadosboleto["nosso_numero"] = $banco[0]->sequencia; //  "13871";             // Nosso numero (máx. 5 digitos) - Numero sequencial de controle.
        $dadosboleto["numero_documento"] = $recibos[0]->idrecibo; //"27.030195.10";  // Num do pedido ou do documento
        $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
        $dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
        $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
        $dadosboleto["valor_boleto"] = $valor_boleto;   // Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

        // DADOS DO SEU CLIENTE
        $dadosboleto["sacado"] = $recibos[0]->cliente;  //"Nome do seu Cliente";
        $dadosboleto["endereco1"] = $inquilinos[0]->endereco; //"Endereço do seu Cliente";
        $dadosboleto["endereco2"] = $inquilinos[0]->bairro."-".$inquilinos[0]->cidade."-".$inquilinos[0]->uf."-".$inquilinos[0]->cep; //"Cidade - Estado -  CEP: 00000-000";

        // INFORMACOES PARA O CLIENTE
        $dadosboleto["demonstrativo1"] = "Referente ao Aluguel do periodo ".$recibos[0]->mes_ano; //"Pagamento de Compra na Loja Nonononono";
        $dadosboleto["demonstrativo2"] = $banco[0]->desc_demonstrativo2; //"Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
        $dadosboleto["demonstrativo3"] = $banco[0]->desc_demonstrativo3; //"BoletoPhp - http://www.boletophp.com.br";

        // INSTRUÇÕES PARA O CAIXA
        $dadosboleto["instrucoes1"] = $banco[0]->instrucao1; //"- Sr. Caixa, cobrar multa de 2% após o vencimento";
        $dadosboleto["instrucoes2"] = $banco[0]->instrucao2; //"- Receber até 10 dias após o vencimento";
        $dadosboleto["instrucoes3"] = $banco[0]->instrucao3; //"- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br";
        $dadosboleto["instrucoes4"] = "";

        // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
        $dadosboleto["quantidade"] = "";
        $dadosboleto["valor_unitario"] = "";
        $dadosboleto["aceite"] = "N";       // N - remeter cobrança sem aceite do sacado  (cobranças não-registradas)
                                        // S - remeter cobrança apos aceite do sacado (cobranças registradas)
        $dadosboleto["especie"] = "R$";
        $dadosboleto["especie_doc"] = "A"; // OS - Outros segundo manual para cedentes de cobrança SICREDI


        // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


        // DADOS DA SUA CONTA - SICREDI
        $dadosboleto["agencia"] = $banco[0]->agencia; //"1234";   // Num da agencia (4 digitos), sem Digito Verificador
        $dadosboleto["conta"] = $banco[0]->conta; //"12345";    // Num da conta (5 digitos), sem Digito Verificador
        $dadosboleto["conta_dv"] = $banco[0]->digito_conta; //"6";     // Digito Verificador do Num da conta

        // DADOS PERSONALIZADOS - SICREDI
        $dadosboleto["posto"]= $banco[0]->digito_agencia; //"18";      // Código do posto da cooperativa de crédito
        $dadosboleto["byte_idt"]= $banco[0]->byte_idt; //"2";    // Byte de identificação do cedente do bloqueto utilizado para compor o nosso número.
                                        // 1 - Idtf emitente: Cooperativa | 2 a 9 - Idtf emitente: Cedente
        $dadosboleto["carteira"] = $banco[0]->carteira; //"A";   // Código da Carteira: A (Simples)

        // SEUS DADOS
        $dadosboleto["identificacao"] = $empresa[0]->nome; //"SYSAB-WEB ";
        $dadosboleto["cpf_cnpj"] = $empresa[0]->cnpj; //"";
        $dadosboleto["endereco"] = $empresa[0]->endereco; //"Coloque o endereço da sua empresa aqui";
        $dadosboleto["cidade_uf"] = $empresa[0]->cidade."-".$empresa[0]->estado; //"Cidade / Estado";
        $dadosboleto["cedente"] = $empresa[0]->fantasia; //"Coloque a Razão Social da sua empresa aqui";

        //return view("Contracts.Cnab.Remessa",[
        return view("Cnab.Remessa.Cnab240.Banco.sicredi",[
            "dadosboleto"=>$dadosboleto,
            "detalhes"=>$detalhes,
            "banco"=>$banco,
            "empresa"=>$empresa,
        ]);

    }

}

