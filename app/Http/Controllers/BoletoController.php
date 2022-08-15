<?php

namespace App\Http\Controllers;

use App\Banco;
use App\Empresa;
use App\Remessa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\BoletoTrait;
use Illuminate\Support\Str;
use ZipArchive;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Eduardokum\LaravelBoleto\Cnab\Remessa\Cnab400\Banco\Sicredi as SicrediRemesa;

class BoletoController extends Controller
{
    use BoletoTrait;

    public $numeroDocumento, $i;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $empresas = DB::table('empresa as emp')
            ->get();

        $proprietarios = DB::table('proprietario as p')
            ->where('p.condicao', '=', 'Ativo')
            ->get();

        $imoveis = DB::table('imovel')->get();
        $municipios = DB::table('municipio')->get();

        $inquilinos = DB::table('inquilino as i')
            ->join('proprietario as p', 'i.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'i.idimovel', '=', 'im.idimovel')
            ->select('i.idinquilino', 'i.idproprietario', 'p.nome as nomepro', 'i.idimovel', 'i.idmunicipio', 'i.tipo_pessoa', 'i.nome', 'i.fantasia', 'i.fisica_juridica', 'i.cpf_cnpj', 'i.endereco as endinq', 'i.telefone', 'i.email', 'i.complemento', 'i.bairro', 'i.cidade', 'i.uf', 'i.cep', 'i.referencia', 'i.obs', 'i.rg_ie', 'i.condicao', 'i.conjuge', 'i.aos_cuidados', 'i.end_corr', 'i.num_corr', 'i.compl_corr', 'i.bairro_corr', 'i.cidade_corr', 'i.uf_corr', 'i.cep_corr', 'i.favorecido', 'i.cpf_fav', 'i.banco_fav', 'i.ag_fav', 'i.conta_fav', 'i.ult_extrato', 'i.data_ult_extrato', 'i.irrf', 'i.locacao_encerada', 'i.dt_enc_locacao', 'i.ult_recibo', 'im.endereco as endimovel')
            ->get();

        $imoveis = DB::table('imovel as im')
            ->where('im.condicao', '=', 'Ativo')->get();

        $indices = DB::table('indice as ind')->get();

        if ($request) {
            $dtInicial = trim($request->get('dtVectoInicial'));
            $dtFinal = trim($request->get('dtVectoFinal'));
            $query = trim($request->get('searchText'));

            if ($dtInicial == "") {
                $dtInicial = '2001-01-01';
            }
            if ($dtFinal == "") {
                $dtFinal = Carbon::now();
            }

            $recibos = DB::table('recibo as r')
                ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
                ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
                ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
                ->where('r.idlocacao', 'LIKE', '%' . $query . '%')
                ->where('r.idrecibo', 'LIKE', '%' . $query . '%')
                ->where('r.codigo', 'LIKE', '%' . $query . '%')
                ->where('r.mes_ano', 'LIKE', '%' . $query . '%')
                ->where('im.codigo', 'LIKE', '%' . $query . '%')
                ->where('im.endereco', 'LIKE', '%' . $query . '%')
                ->where('i.nome', 'LIKE', '%' . $query . '%')
                ->where('p.nome', 'LIKE', '%' . $query . '%')
                ->orderBy('r.dt_vencimento', 'desc')
                ->get();


            return view('reports.boleto.index', [
                "recibos" => $recibos,
                "proprietarios" => $proprietarios,
                "imoveis" => $imoveis,
                "inquilinos" => $inquilinos,
                "empresas" => $empresas,
                "searchText" => $query
            ]);
        }
    }

    public function pdfBoletos(Request $request)
    {

        $empresa=DB::table('empresa as emp')->get();

        $inquilinos = DB::table('inquilino as i')
            ->where('i.condicao', '=', 'Ativo')
            ->get();

        $proprietarios = DB::table('proprietario as p')
            ->where('p.condicao', '=', 'Ativo')
            ->get();

        $imoveis = DB::table('imovel as im')
            ->where('im.condicao', '=', 'Ativo')->get();

        $indices = DB::table('indice as ind')->get();

        $banco=DB::table('banco as ban')
        ->join('empresa as e', 'ban.codigo', '=', 'e.banco_padrao_boleto')
        ->get();
        $regban=$banco[0]->idbanco;
        $ultima_remessa=$banco[0]->ultima_remessa;

        $data = Carbon::now()->toDateTimeString();

        $dia = substr($data, 8, 2);
        $mes = substr($data, 5, 2);
        $ano = substr($data, 0, 4);
        $data1 = Carbon::create($ano, $mes, $dia, 0, 0)->toDateTimeString();

        if ($request) {
            $query = trim($request->get('searchText'));
            $dtInicial = trim($request->get('dtVectoInicial'));
            $dtFinal = trim($request->get('dtVectoFinal'));
            $idinquilino_filtro = trim($request->get('idpinquilino'));
            $idproprietario_filtro = trim($request->get('idproprietario'));

            $dados = $request->get('idpinquilino');
            $array = explode('_', $dados);
            $idTodosinquilinos = $array[0];

            $dados = $request->get('idproprietario');
            $array = explode('_', $dados);
            $idTodosProprietarios = $array[0];

            if ($dtInicial == "") {
                $dtInicial = '2001-01-01';
            }
            if ($dtFinal == "") {
                $dtFinal = '4001-01-01';
            }

            if ($idTodosinquilinos == 'Todos' and $idTodosProprietarios == 'Todos') {
                $recibos = DB::table('recibo as r')->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                    ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                    ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                    ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                    ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                    ->select('idremessa','r.idrecibo', 'r.mes_ano', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.telefone', 'p.nome as nomepro', 'im.codigo as codigoimo', 'im.endereco', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.valor_pgto', 'r.forma_pgto', 'r.total_aluguel')
                    ->where('r.idremessa', Null)
                    ->where('r.dt_pagamento', Null)
                    ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
                    ->orderBy('r.dt_vencimento', 'asc')
                    ->get();
                $total_relatorio = $recibos->sum('total_aluguel');
            }
            if ($idTodosinquilinos != 'Todos' and $idTodosProprietarios == 'Todos') {
                $inquilinos = DB::table('inquilino as i')
                    ->where('i.idinquilino', $idinquilino_filtro)
                    ->get();

                $recibos = DB::table('recibo as r')->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                    ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                    ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                    ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                    ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                    ->select('r.idrecibo', 'r.mes_ano', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.telefone', 'p.nome as nomepro', 'im.codigo as codigoimo', 'im.endereco', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.valor_pgto', 'r.forma_pgto', 'r.total_aluguel')
                    ->where('idremessa','r.dt_pagamento', '=', NULL)
                    ->where('r.idremessa', Null)
                    ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
                    ->where('r.idinquilino', 'LIKE', $idinquilino_filtro)
                    ->orderBy('r.dt_vencimento', 'asc')
                    ->get();
                $total_relatorio = $recibos->sum('total_aluguel');
            }

            if ($idTodosinquilinos == 'Todos' and $idTodosProprietarios != 'Todos') {
                $recibos = DB::table('recibo as r')
                    ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                    ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                    ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                    ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                    ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                    ->select('idremessa','r.idrecibo', 'r.mes_ano', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.telefone', 'p.nome as nomepro', 'im.codigo as codigoimo', 'im.endereco', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.valor_pgto', 'r.forma_pgto', 'r.total_aluguel')
                    ->where('r.dt_pagamento', '=', NULL)
                    ->where('r.idremessa', Null)
                    ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
                    ->where('r.idproprietario', 'LIKE', $idproprietario_filtro)
                    ->orderBy('r.dt_vencimento', 'asc')
                    ->get();

                $total_relatorio = $recibos->sum('total_aluguel');
            }

            if ($idTodosinquilinos != 'Todos' and $idTodosProprietarios != 'Todos') {
                $recibos = DB::table('recibo as r')
                    ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                    ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                    ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                    ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                    ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                    ->select('idremessa','r.idrecibo', 'r.mes_ano', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.telefone', 'p.nome as nomepro', 'im.codigo as codigoimo', 'im.endereco', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.forma_pgto', 'r.total_aluguel')
                    ->where('r.dt_pagamento', '=', NULL)
                    ->where('r.idremessa', Null)
                    ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
                    ->where('r.idproprietario', 'LIKE', $idproprietario_filtro)
                    ->where('r.idinquilino', 'LIKE', $idinquilino_filtro)
                    ->orderBy('r.dt_vencimento', 'asc')
                    ->get();
                $total_relatorio = $recibos->sum('total_aluguel');
            }
        }

        $i = '';

        if ($recibos->count() != 0)
        {
            $oldfiles = File::files(public_path('boletos'));
            File::delete($oldfiles);

            $empresa = Empresa::where('idempresa',1)->first();
            $banco = Banco::where('idbanco',2)->first();

            $beneficiario = new \Eduardokum\LaravelBoleto\Pessoa;
            $beneficiario->setDocumento($empresa->cnpj)
            ->setNome($empresa->nome)
            ->setCep($empresa->cep)
            ->setEndereco($empresa->endereco)
            ->setBairro($empresa->bsirro)
            ->setUf($empresa->estado)
            ->setCidade($empresa->cidade);

            /* Boletos Geraise o Individual BoletoSicrediController */
            $banco_up = DB::table('banco')->where('idbanco', $regban)->update([
                'ultima_remessa' => $banco->ultima_remessa+1,
            ]);

            $ultima_remessa=$banco->ultima_remessa;

            $dia = "";
            $mes = "";
            $dia = substr($data, 8, 2);
            $mes = substr($data, 5, 2);
            if ($mes=="01") {$mes='1';}
            if ($mes=="02") {$mes='2';}
            if ($mes=="03") {$mes='3';}
            if ($mes=="04") {$mes='4';}
            if ($mes=="05") {$mes='5';}
            if ($mes=="06") {$mes='6';}
            if ($mes=="07") {$mes='7';}
            if ($mes=="08") {$mes='8';}
            if ($mes=="09") {$mes='9';}
            if ($mes=="10") {$mes='O';}
            if ($mes=="11") {$mes='N';}
            if ($mes=="12") {$mes='D';}

            $remessa = DB::table('remessas as r')
            ->select('r.id','r.created_at')
            ->where('r.created_at', '>=', $data1)
            ->get();

            $contador=$remessa->count();

            if ($remessa->isEmpty()) { //Verdadeiro
                if ($contador==0) {
                    $nome=$banco->cod_cedente.$mes.$dia.'.crm';
                }

            } else { //Falso
                if ($contador !=0) {
                    $nome=$banco->cod_cedente.$mes.$dia.'.rm'.$contador;
                }
            }

            $novaremessa = new Remessa;
            $novaremessa->id = $ultima_remessa;
            $novaremessa->path_remessa = $nome;
            $novaremessa->save();

            $remessa = Remessa::latest('id')->first();
            $idremessa = $remessa->id;

            /*Cria cada boleto e o guarda em array*/
            foreach ($recibos as $recibo) {
                    $idrec = $recibo->idrecibo;
                    $recibo_up = DB::table('recibo')
                        ->where('idrecibo', $idrec)
                        ->update([
                        'idremessa' => $idremessa,
                        'nomeremessa'=> $nome
                    ]);

                $boleto = $this->printBoleto($idrec);
                $boletos[] = $boleto;
            }

            /*Pega o array de boletos e gera a remessa grupal */
            $send = new SicrediRemesa([
                'agencia'      => $banco->agencia,
                'carteira'     => $banco->carteira,
                'conta'        => $banco->conta,
                'codigoCliente'=> $banco->codigo_cliente,
                'idremessa'    => $ultima_remessa,
                'beneficiario' => $beneficiario,
            ]);
            $send->addBoletos($boletos);

            $send->gerar();
            $send->save(public_path('boletos/'.$nome));

            /*Pega todos os documentos do pagador de cada boleto e o guarda em um array*/
            foreach ($boletos as $boleto) {
                $documentosarray[] = $boleto->getPagador()->getDocumento();
            };

            /*Tira os documentos repetidos*/
            $documentos = array_unique($documentosarray);
            /*conta a quantidade de documentos*/
            $number = count($documentos);

            /*coloca em keys ordenadas cada documento */
            $i = 0;
            foreach ($documentos as $documento) {
                $docs[$i] = $documento;
                $i++;
            }
            /*Cria uma key para identificar o grupo de pdfs*/
            //$key = Str::random(8);
            $key = $nome.'-remessa'.$ultima_remessa;

            /*Cria os boletos por docuemtno e guarda eles no storage*/
            for ($i = 0; $i < $number; $i++) {
                foreach ($boletos as $boleto) {
                    if ($docs[$i] == $boleto->getPagador()->getDocumento()) {
                        $boletosSave[] = $boleto;
                        $nomeboleto = 'boletos_'.$boleto->getPagador()->getNome().'_'.$boleto->getPagador()->getDocumento();
                    }
                }

                $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
                $pdf->addBoletos($boletosSave, false);
                $pdf->gerarBoleto($pdf::OUTPUT_SAVE, public_path('boletos/'.$nomeboleto.'.pdf'));
                /*Apaga o conteudo do array boltosSave */
                unset($boletosSave);
            }

            $zip = new ZipArchive;

            $fileName = 'boletos/'.$key.'-boletos.zip'; // nome do zip
            $zipPath = public_path($fileName); // path do zip

            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                // arquivos que serao adicionados ao zip
                $files = File::files(public_path('boletos'));

                foreach ($files as $key => $value) {
                    // nome/diretorio do arquivo dentro do zip
                    $relativeNameInZipFile = basename($value);
                    // adicionar arquivo ao zip
                    $zip->addFile($value, $relativeNameInZipFile);
                }
                // concluir a operacao
                $zip->close();
            }

            File::delete($files);

            return response()->download($zipPath);
        } else {

            return back()->with(
                'warning',
                'Não há boletos para esta selecão'
            );
        }
    }
}
