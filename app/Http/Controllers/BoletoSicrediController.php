<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recibo;
use App\DetalheRecibo;
use App\Remessa;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ReciboFormRequest;
use Illuminate\Support\Facades\DB;


use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

use Eduardokum\LaravelBoleto\Pessoa as Pessoa;
use Eduardokum\LaravelBoleto\Boleto\Banco\Sicredi as Sicredi;
use Eduardokum\LaravelBoleto\Cnab\Remessa\Cnab400\Banco\Sicredi as SicrediRemesa;
//use Eduardokum\LaravelBoleto\Cnab\Remessa\Cnab240\Banco\Sicredi as SicrediRemesa;

class BoletoSicrediController extends Controller
{
    public function index($id){

        //$id=298;
        //Pegar os dado das tabelas

        //dd(storage_path());die();
        $empresa=DB::table('empresa as emp')->get();


        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->where('im.condicao','=','Ativo')->get();

        $banco=DB::table('banco as ban')
        ->join('empresa as e', 'ban.codigo', '=', 'e.banco_padrao_boleto')
        ->get();
        $regban=$banco[0]->idbanco;
        $ultima_remessa=$banco[0]->ultima_remessa;

        //dd($regban);die();

        $indices=DB::table('indice as ind')->get();

        $recibos = DB::table('recibo as r')
        ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
        ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
        ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
        ->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
        ->select('r.idremessa','r.idlocacao','r.idrecibo','l.reajuste_sobre as valor_boleto','r.idrecibo as numero_documento',
        'r.mes_ano','l.idlocacao', 'i.nome as cliente','p.nome as nomeprop','p.cpf_cnpj as cpfprop','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste',
        'r.dt_vencimento as data_vencimento','i.nome as sacado','i.endereco as endereco1','i.cidade as endereco2','r.dt_inicial','r.dt_final')
        ->where('r.idrecibo','=', $id)
        ->get();

        $idrec = $recibos[0]->idrecibo;
        $detalhes=DB::table('detalhe_recibo as d')
        ->join('recibo as r','d.idrecibo','=','r.idrecibo')
        ->join('evento as e','d.idevento','=','e.idevento')
        ->select('d.idrecibo','r.idlocacao','e.nome as evento', 'd.complemento', 'd.qtde', 'd.valor', 'd.mes_ano_det', 'd.qtde_limite','e.idevento','e.tipo')
        ->where('d.idrecibo','=', $idrec)
        ->get();

        // DADOS DO BOLETO PARA O SEU CLIENTE
        // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //

        $beneficiario = new Pessoa([
            'documento' => $empresa[0]->cnpj, //'00.000.000/0000-00',
            'nome'      => $empresa[0]->nome." CRECI - ".$empresa[0]->creci, //'Company co.',
            'cep'       => $empresa[0]->cep, //'00000-000',
            'endereco'  => $empresa[0]->endereco, //'Street name, 123',
            'bairro'    => $empresa[0]->bairro, //'district',
            'uf'        => $empresa[0]->estado, //'UF',
            'cidade'    => $empresa[0]->cidade, //'City',
        ]);

        $pagador = new Pessoa(
            [
                'nome'      => $recibos[0]->cliente, //'Cliente',
                'endereco'  => $imoveis[0]->endereco, //'Rua um, 123',
                'bairro'    => $imoveis[0]->bairro ,//'Bairro',
                'cep'       => $imoveis[0]->cep, //'99999-999',
                'uf'        => $imoveis[0]->uf, //'UF',
                'cidade'    => $imoveis[0]->cidade, //'CIDADE',
                'documento' => $inquilinos[0]->cpf_cnpj, //'999.999.999-99',
            ]
        );

        $demonstrativo1="";
        $descricao="";
        $espaco="";
        $suma=0;
        $signo="";
        foreach ($detalhes as $key) {
            if($key->tipo == "Credito"){
                $signo = "(+)";
                $suma=$suma + $key->valor;
            }else{
                $signo = "(- )";
                $suma=$suma - $key->valor;
            }
            $i=0;
            if ($key->qtde==null) {
                $descricao=$descricao.$key->evento;
            }
            if ($key->qtde!=null) {
                $descricao=$descricao.$key->evento."\t\t".$key->complemento."\t\t".$key->qtde."-".$key->qtde_limite;
            }
            for($i=strlen($descricao); $i < 60; $i++){
                $espaco=$espaco.".";
            }
            // $demonstrativo1=$demonstrativo1.$signo."\t\t\t\t"."00".$key->idevento."\t\t\t\t\t\t\t\t\t\t".$key->evento."\t\t\t\t\t\t\t\t\t\t"."R$ ".number_format($key->valor, 2, ',', '.')."\n";
            $demonstrativo1=$demonstrativo1.$signo."\t\t\t\t"."00".$key->idevento."\t\t\t\t\t\t\t\t\t\t".$descricao.$espaco."R$ ".number_format($key->valor, 2, ',', '.')."\n";
            $espaco="";
            $descricao="";
        }


        $demonstrativo2="\n"."TOTAL"."\t\t\t\t\t\t\t\t\t\t"."R$ ".number_format($suma, 2, ',', '.')."\n";
        $demonstrativo3="\n"."Proprietário.: ".$recibos[0]->nomeprop."  CPF.: ".$recibos[0]->cpfprop." ".$imoveis[0]->obs."\n"."Imóvel..........: ".$imoveis[0]->endereco." - ".$imoveis[0]->bairro." - ".$imoveis[0]->cidade."\n"."Periodo ........: ".\Carbon\Carbon::parse($recibos[0]->dt_inicial)->format('d/m/Y')." a ".\Carbon\Carbon::parse($recibos[0]->dt_final)->format('d/m/Y')." - Nº Recibo ......: ".$recibos[0]->contador_aluguel."/".$recibos[0]->reajuste;

        $logo=$empresa[0]->logo_url;
        if ($logo=='null') {
            $logo='logo_empresa.png';
        } else {
        }

        //dd($logo,$empresa);

        $dataVencimento = Carbon::now();
        $sicredi = new Sicredi([
            'logo' => '../public/imagens/Boletos/'.$logo,
            'dataVencimento' => $dataVencimento,
            'valor' =>  $suma, //100,
            'numero' => $recibos[0]->idrecibo."    Ref .: ".$recibos[0]->mes_ano."    Periodo .: ".\Carbon\Carbon::parse($recibos[0]->dt_inicial)->format('d/m/Y')." a ".\Carbon\Carbon::parse($recibos[0]->dt_final)->format('d/m/Y'), //1,
            'numeroDocumento' => $recibos[0]->idrecibo, // 1,
            'pagador' => $pagador,
            'beneficiario' => $beneficiario,
            'carteira' => $banco[0]->carteira, //1,
            'posto' => $banco[0]->posto, //11,
            'byte' => $banco[0]->byte_idt, //2,
            'agencia' => $banco[0]->agencia, //1111,
            'conta' => $banco[0]->conta, //22222,
            'codigoCliente' => $banco[0]->codigo_cliente, //12345,
            'multa' => $banco[0]->multa, //1, // 1% do valor do boleto após o vencimento
            'juros' => $banco[0]->juros, //1, // 1% ao mês do valor do boleto
            'jurosApos' => $banco[0]->jurosapos, //0, // quant. de dias para começar a cobrança de juros,
            //'descricaoDemonstrativo' => ['demonstrativo 1', 'demonstrativo 2', 'demonstrativo 3',' 4','5' ],
            'descricaoDemonstrativo' => [$demonstrativo1,$demonstrativo2,$demonstrativo3],
            'instrucoes' => [$banco[0]->instrucao1, $banco[0]->instrucao2, $banco[0]->instrucao3],
        ]);

        //dd($sicredi);

        //contador de remessa
        //$idremessa=$recibos[0]->idremessa;
        // if ($recibos[0]->idremessa=="") {
        //     $banco_up = DB::table('banco')
        //         ->where('idbanco', $regban)
        //         ->update([
        //         'ultima_remessa' => $banco[0]->ultima_remessa+1,
        //     ]);
        //     $ultima_remessa=$banco[0]->ultima_remessa;
        // }


        // $novaremessa = new Remessa;
        // $novaremessa->path_remessa = '';
        // $novaremessa->save();

        $remessa = Remessa::latest('id')->first();
        $idremessa = $remessa->id;


        //dd($idremessa);die();

        //////////////Boleto Individual \\\\\\\ e o Geral BoletoController pdfBoletos
        $remessa = new SicrediRemesa(
            [
                'agencia'      => $banco[0]->agencia, //2606,
                'carteira'     => $banco[0]->carteira, //'1',
                'conta'        => $banco[0]->conta, //12510,
                'codigoCliente'=> $banco[0]->codigo_cliente, //12345,
                'idremessa'    => $ultima_remessa, //$idremessa, //1,
                'beneficiario' => $beneficiario,
            ]
        );

        //dd($banco,$recibos,$remessa);
        $nomeboleto='boleto_'.$recibos[0]->cliente.'_'.$recibos[0]->idrecibo.'_'.$recibos[0]->mes_ano;

        if ($recibos[0]->idremessa!="") {
            $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
            $pdf->addBoleto($sicredi);
            //$pdf->gerarBoleto($pdf::OUTPUT_DOWNLOAD);
            // $pdf->gerarBoleto($pdf::OUTPUT_STANDARD,$banco[0]->path_remessa. DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'sicredi.pdf');
            //dd('achei 5');

            $pdf->gerarBoleto($pdf::OUTPUT_DOWNLOAD,'T:\power',$nomeboleto);
        }
        //dd('achei 6');

        $nome='remessa_'.$recibos[0]->cliente.'_'.$recibos[0]->idrecibo.'_'.$idremessa.'.txt';

        if ($recibos[0]->idremessa=="") {
            // $recibo_up = DB::table('recibo')
            //     ->where('idrecibo', $idrec)
            //     ->update([
            //     'idremessa' => $idremessa,
            //     'nomeremessa'=> $nome
            // ]);
            // $pathremessa = DB::table('remessas')
            //     ->where('id', $idremessa)
            //     ->update([
            //         'path_remessa'=> $nome
            // ]);

            //echo var_dump(__DIR__);
            //$pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
            $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
            $pdf->addBoleto($sicredi);
            //$pdf->gerarBoleto($pdf::OUTPUT_DOWNLOAD);
            $pdf->gerarBoleto($pdf::OUTPUT_DOWNLOAD,'T:\power',$nomeboleto);

            $nome='remessa_'.$recibos[0]->cliente.'_'.$recibos[0]->idrecibo.'_'.$idremessa.'.crm';
            $path = public_path().DIRECTORY_SEPARATOR.'remessas'.DIRECTORY_SEPARATOR.$nome;

            $remessa->addBoleto($sicredi);
            $remessa->save($path);
        }
    }

    public function ImpressaoPdfBoleto($id){

        //$id=298;
        //Pegar os dado das tabelas

        //dd($id);die();
        $empresa=DB::table('empresa as emp')->get();


        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->where('im.condicao','=','Ativo')->get();

        $banco=DB::table('banco as ban')
        ->join('empresa as e', 'ban.codigo', '=', 'e.banco_padrao_boleto')
        ->get();
        $regban=$banco[0]->idbanco;

        //dd($regban);die();

        $indices=DB::table('indice as ind')->get();

        $recibos = DB::table('recibo as r')
        ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
        ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
        ->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
        ->select('r.idremessa','r.idlocacao','r.idrecibo','l.reajuste_sobre as valor_boleto','r.idrecibo as numero_documento','r.mes_ano','l.idlocacao', 'i.nome as cliente','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','r.dt_vencimento as data_vencimento','i.nome as sacado','i.endereco as endereco1','i.cidade as endereco2')
        ->where('r.idrecibo','=', $id)
        ->get();

        $idrec = $recibos[0]->idrecibo;
        $detalhes=DB::table('detalhe_recibo as d')
        ->join('recibo as r','d.idrecibo','=','r.idrecibo')
        ->join('evento as e','d.idevento','=','e.idevento')
        ->select('d.idrecibo','r.idlocacao','e.nome as evento', 'd.complemento', 'd.qtde', 'd.valor', 'd.mes_ano_det', 'd.qtde_limite','e.idevento','e.tipo')
        ->where('d.idrecibo','=', $idrec)
        ->get();

        // DADOS DO BOLETO PARA O SEU CLIENTE
        // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //

        $beneficiario = new Pessoa([
            'documento' => $empresa[0]->cnpj, //'00.000.000/0000-00',
            'nome'      => $empresa[0]->nome, //'Company co.',
            'cep'       => $empresa[0]->cep, //'00000-000',
            'endereco'  => $empresa[0]->endereco, //'Street name, 123',
            'bairro'    => $empresa[0]->bairro, //'district',
            'uf'        => $empresa[0]->estado, //'UF',
            'cidade'    => $empresa[0]->cidade, //'City',
        ]);

        $pagador = new Pessoa(
            [
                'nome'      => $recibos[0]->cliente, //'Cliente',
                'endereco'  => $inquilinos[0]->endereco, //'Rua um, 123',
                'bairro'    => $inquilinos[0]->bairro ,//'Bairro',
                'cep'       => $inquilinos[0]->cep, //'99999-999',
                'uf'        => $inquilinos[0]->uf, //'UF',
                'cidade'    => $inquilinos[0]->cidade, //'CIDADE',
                'documento' => $inquilinos[0]->cpf_cnpj, //'999.999.999-99',
            ]
        );

        $demonstrativo1="";
        $descricao="";
        $espaco="";
        $suma=0;
        $signo="";
        foreach ($detalhes as $key) {
            if($key->tipo == "Credito"){
                $signo = "(+)";
                $suma=$suma + $key->valor;
            }else{
                $signo = "(- )";
                $suma=$suma - $key->valor;
            }
            $i=0;
            if ($key->qtde==null) {
                $descricao=$descricao.$key->evento;
            }
            if ($key->qtde!=null) {
                $descricao=$descricao.$key->evento."\t\t".$key->complemento."\t\t".$key->qtde."-".$key->qtde_limite;
            }
            for($i=strlen($descricao); $i < 60; $i++){
                $espaco=$espaco.".";
            }
            // $demonstrativo1=$demonstrativo1.$signo."\t\t\t\t"."00".$key->idevento."\t\t\t\t\t\t\t\t\t\t".$key->evento."\t\t\t\t\t\t\t\t\t\t"."R$ ".number_format($key->valor, 2, ',', '.')."\n";
            $demonstrativo1=$demonstrativo1.$signo."\t\t\t\t"."00".$key->idevento."\t\t\t\t\t\t\t\t\t\t".$descricao.$espaco."R$ ".number_format($key->valor, 2, ',', '.')."\n";
            $espaco="";
            $descricao="";
        }

        $demonstrativo2="\n"."TOTAL"."\t\t\t\t\t\t\t\t\t\t"."R$ ".number_format($suma, 2, ',', '.')."\n";
        $demonstrativo3="\n".$imoveis[0]->endereco." - ".$imoveis[0]->bairro." - ".$imoveis[0]->cidade;

        $dataVencimento = Carbon::now();
        $sicredi = new Sicredi([
            'logo' => '../public/imagens/Boletos/logo_empresa.png',
            'dataVencimento' => $dataVencimento,
            'valor' =>  $suma, //100,
            'numero' => $recibos[0]->idrecibo, //1,
            'numeroDocumento' => $recibos[0]->idrecibo, // 1,
            'pagador' => $pagador,
            'beneficiario' => $beneficiario,
            'carteira' => $banco[0]->carteira, //1,
            'posto' => $banco[0]->posto, //11,
            'byte' => $banco[0]->byte_idt, //2,
            'agencia' => $banco[0]->agencia, //1111,
            'conta' => $banco[0]->conta, //22222,
            'codigoCliente' => $banco[0]->codigo_cliente, //12345,
            'multa' => $banco[0]->multa, //1, // 1% do valor do boleto após o vencimento
            'juros' => $banco[0]->juros, //1, // 1% ao mês do valor do boleto
            'jurosApos' => $banco[0]->jurosapos, //0, // quant. de dias para começar a cobrança de juros,
            //'descricaoDemonstrativo' => ['demonstrativo 1', 'demonstrativo 2', 'demonstrativo 3',' 4','5' ],
            'descricaoDemonstrativo' => [$demonstrativo1,$demonstrativo2,$demonstrativo3],
            'instrucoes' => [$banco[0]->instrucao1, $banco[0]->instrucao2, $banco[0]->instrucao3],
        ]);

        //dd($sicredi);

        //contador de remessa
        //$idremessa=$recibos[0]->idremessa;
        if ($recibos[0]->idremessa=="") {
            $banco_up = DB::table('banco')
              ->where('idbanco', $regban)
              ->update([
                'ultima_remessa' => $banco[0]->ultima_remessa+1,
            ]);
            $ultima_remessa=$banco[0]->ultima_remessa;
        }

        // $novaremessa = new Remessa;
        // $novaremessa->path_remessa = '';
        // $novaremessa->save();

         $remessa = Remessa::latest('id')->first();
         $idremessa = $remessa->id;


       //dd($idremessa);die();

        $remessa = new SicrediRemesa(
            [
                'agencia'      => $banco[0]->agencia, //2606,
                'carteira'     => $banco[0]->carteira, //'1',
                'conta'        => $banco[0]->conta, //12510,
                'codigoCliente'=> $banco[0]->codigo_cliente, //12345,
                'idremessa'    => $idremessa, //1,
                'beneficiario' => $beneficiario,
            ]
        );

        //dd($banco,$recibos,$remessa);
        $nomeboleto='boleto_'.$recibos[0]->cliente.'_'.$recibos[0]->idrecibo.'_'.$recibos[0]->mes_ano;

        if ($recibos[0]->idremessa!="") {
            $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
            $pdf->addBoleto($sicredi);
            //$pdf->gerarBoleto($pdf::OUTPUT_DOWNLOAD);
           // $pdf->gerarBoleto($pdf::OUTPUT_STANDARD,$banco[0]->path_remessa. DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'sicredi.pdf');

           $pdf->gerarBoleto($pdf::OUTPUT_DOWNLOAD,'T:\power',$nomeboleto);
        }

        $nome='remessa_'.$recibos[0]->cliente.'_'.$recibos[0]->idrecibo.'_'.$idremessa.'.crm';

        //dd($nome);

        if ($recibos[0]->idremessa=="") {
            // $recibo_up = DB::table('recibo')
            //   ->where('idrecibo', $idrec)
            //   ->update([
            //     'idremessa' => $idremessa,
            //     'nomeremessa'=> $nome
            // ]);
            // $pathremessa = DB::table('remessas')
            //     ->where('id', $idremessa)
            //     ->update([
            //         'path_remessa'=> $nome
            // ]);

            //echo var_dump(__DIR__);
            $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();

            //dd($pdf,'BoletoSicredi ');
            $pdf->addBoleto($sicredi);
            //$pdf->gerarBoleto($pdf::OUTPUT_DOWNLOAD);
            $pdf->gerarBoleto($pdf::OUTPUT_DOWNLOAD,'T:\power',$nomeboleto);

            $nome='remessa_'.$recibos[0]->cliente.'_'.$recibos[0]->idrecibo.'_'.$idremessa.'.crm';
            $path = public_path().DIRECTORY_SEPARATOR.'remessas'.DIRECTORY_SEPARATOR.$nome;

            $remessa->addBoleto($sicredi);
            $remessa->save($path);
        }
    }

}
