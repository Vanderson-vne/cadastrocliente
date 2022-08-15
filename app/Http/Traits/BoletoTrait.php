<?php

namespace App\Http\Traits;

use App\Banco;
use App\Imovel;
use App\Inquilino;
use App\Remessa;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use Eduardokum\LaravelBoleto\Pessoa as Pessoa;
use Eduardokum\LaravelBoleto\Boleto\Banco\Sicredi as Sicredi;
use Eduardokum\LaravelBoleto\Cnab\Remessa\Cnab400\Banco\Sicredi as SicrediRemesa;

trait BoletoTrait
{
    public function printBoleto($id)
    {
        $empresa=DB::table('empresa as emp')->first();
        //$empresa=DB::table('empresa as emp')->get();

        $recibo = DB::table('recibo as r')
        ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
        ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
        ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
        ->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
        ->select('r.idremessa','r.idlocacao','r.idrecibo','l.reajuste_sobre as valor_boleto','r.idrecibo as numero_documento','r.mes_ano','l.idlocacao', 'i.nome as cliente',
        'p.nome as nomeprop','p.cpf_cnpj as cpfprop','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','r.dt_vencimento as data_vencimento','i.nome as sacado','i.endereco as endereco1','i.cidade as endereco2','i.cpf_cnpj','r.dt_inicial','r.dt_final','r.idinquilino','r.idimovel')
        ->where('r.idrecibo','=', $id)
        ->first();

        $inquilino=Inquilino::where('condicao','=','Ativo')->where('idinquilino',$recibo->idinquilino)->first();
        $imovel = Imovel::where('idimovel',$recibo->idimovel)->first();
        $banco = Banco::join('empresa','banco.codigo', '=', 'empresa.banco_padrao_boleto')->first();

        $detalhes=DB::table('detalhe_recibo as d')
        ->join('recibo as r','d.idrecibo','=','r.idrecibo')
        ->join('evento as e','d.idevento','=','e.idevento')
        ->select('d.idrecibo','r.idlocacao','e.nome as evento', 'd.complemento', 'd.qtde', 'd.valor', 'd.mes_ano_det', 'd.qtde_limite','e.idevento','e.tipo')
        ->where('d.idrecibo','=', $recibo->idrecibo)
        ->get();

        $beneficiario = new Pessoa([
            'documento' => $empresa->cnpj,
            'nome'      => $empresa->nome." CRECI - ".$empresa->creci,
            'cep'       => $empresa->cep,
            'endereco'  => $empresa->endereco,
            'bairro'    => $empresa->bairro,
            'uf'        => $empresa->estado,
            'cidade'    => $empresa->cidade,
        ]);

        $pagador = new Pessoa([
            'nome'      => $recibo->cliente,
            'documento' => $inquilino->cpf_cnpj,
            'endereco'  => $imovel->endereco,
            'bairro'    => $imovel->bairro,
            'cep'       => $imovel->cep,
            'uf'        => $imovel->uf,
            'cidade'    => $imovel->cidade,
        ]);

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

            $demonstrativo1=$demonstrativo1.$signo."\t\t\t\t"."00".$key->idevento."\t\t\t\t\t\t\t\t\t\t".$descricao.$espaco."R$ ".number_format($key->valor, 2, ',', '.')."\n";
            $espaco="";
            $descricao="";
        }

        $demonstrativo2="\n"."TOTAL"."\t\t\t\t\t\t\t\t\t\t"."R$ ".number_format($suma, 2, ',', '.')."\n";
        $demonstrativo3="\n"."Proprietário.: ".$recibo->nomeprop."  CPF .:".$recibo->cpfprop." - ".$imovel->obs."\n"."Imóvel..........: ".$imovel->endereco." - ".$imovel->bairro." - ".$imovel->cidade."\n"."Periodo ........: ".\Carbon\Carbon::parse($recibo->dt_inicial)->format('d/m/Y')." a ".\Carbon\Carbon::parse($recibo->dt_final)->format('d/m/Y')." - Nº Recibo .....: ".$recibo->contador_aluguel."/".$recibo->reajuste;

        $dataVencimento = Carbon::parse($recibo->data_vencimento);
        
        $logo=$empresa->logo_url;
        if ($logo=='null') {
            $logo='logo_empresa.png';
        } else {
        }

        $sicredi = new Sicredi([
            'logo' => '../public/imagens/Boletos/'.$logo,
            'dataVencimento' => $dataVencimento,
            'valor' =>  $suma,
            'numero' => $recibo->idrecibo."    Ref .: ".$recibo->mes_ano."    Periodo .: ".\Carbon\Carbon::parse($recibo->dt_inicial)->format('d/m/Y')." a ".\Carbon\Carbon::parse($recibo->dt_final)->format('d/m/Y'),
            'numeroDocumento' => $recibo->idrecibo,
            'pagador' => $pagador,
            'beneficiario' => $beneficiario,
            'carteira' => $banco->carteira,
            'posto' => $banco->posto,
            'byte' => $banco->byte_idt,
            'agencia' => $banco->agencia,
            'conta' => $banco->conta,
            'codigoCliente' => $banco->codigo_cliente,
            'multa' => $banco->multa,
            'juros' => $banco->juros,
            'jurosApos' => $banco->jurosapos,
            'descricaoDemonstrativo' => [$demonstrativo1,$demonstrativo2,$demonstrativo3],
            'instrucoes' => [$banco->instrucao1, $banco->instrucao2, $banco->instrucao3],
        ]);

        return $sicredi;

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

        // $remessa = Remessa::latest('id')->first();
        // $idremessa = $remessa->id;


        // //dd($idremessa);die();

        // $remessa = new SicrediRemesa(
        //     [
        //         'agencia'      => $banco[0]->agencia, //2606,
        //         'carteira'     => $banco[0]->carteira, //'1',
        //         'conta'        => $banco[0]->conta, //12510,
        //         'codigoCliente'=> $banco[0]->codigo_cliente, //12345,
        //         'idremessa'    => $idremessa, //1,
        //         'beneficiario' => $beneficiario,
        //     ]
        // );

        // //dd($banco,$recibos,$remessa);
        // $nomeboleto='boleto_'.$recibos[0]->cliente.'_'.$recibos[0]->idrecibo.'_'.$recibos[0]->mes_ano;

        // if ($recibos[0]->idremessa!="") {
        //     $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
        //     $pdf->addBoleto($sicredi);
        //     //$pdf->gerarBoleto($pdf::OUTPUT_DOWNLOAD);
        //     // $pdf->gerarBoleto($pdf::OUTPUT_STANDARD,$banco[0]->path_remessa. DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'sicredi.pdf');

        //     $pdf->gerarBoleto($pdf::OUTPUT_DOWNLOAD,'T:\power',$nomeboleto);
        // }

        // $nome='remessa_'.$recibos[0]->cliente.'_'.$recibos[0]->idrecibo.'_'.$idremessa.'.txt';

        // if ($recibos[0]->idremessa=="") {
        //     $recibo_up = DB::table('recibo')
        //         ->where('idrecibo', $idrec)
        //         ->update([
        //         'idremessa' => $idremessa,
        //         'nomeremessa'=> $nome
        //     ]);
        //     $pathremessa = DB::table('remessas')
        //         ->where('id', $idremessa)
        //         ->update([
        //             'path_remessa'=> $nome
        //     ]);

        //     //echo var_dump(__DIR__);
        //     $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
        //     $pdf->addBoleto($sicredi);
        //     //$pdf->gerarBoleto($pdf::OUTPUT_DOWNLOAD);
        //     $pdf->gerarBoleto($pdf::OUTPUT_DOWNLOAD,'T:\power',$nomeboleto);

        //     $nome='remessa_'.$recibos[0]->cliente.'_'.$recibos[0]->idrecibo.'_'.$idremessa.'.txt';
        //     $path = public_path().DIRECTORY_SEPARATOR.'remessas'.DIRECTORY_SEPARATOR.$nome;

        //     $remessa->addBoleto($sicredi);
        //     $remessa->save($path);
        // }
    }
}
