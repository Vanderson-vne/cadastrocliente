<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Inquilino;
use App\Proprietario;
use App\Imovel;
use App\Municipio;
use App\Indice;
use App\Locacao;
use App\Recibo;
use App\DetalheRecibo;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ReciboFormRequest;
use PDF;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB as DB;

class GerarPdfController extends Controller
{

    public function PdfProprietarios(){

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->join('proprietario as p', 'im.idproprietario', '=', 'p.idproprietario')
        ->select('im.idimovel','im.nome','p.nome as nomepro','im.endereco')
        //->where('im.condicao','=','Ativo')
        ->where('im.idproprietario','=','p.idproprietario')
        ->get();

    	$pdf = PDF::loadView('reports/pdf_proprietario',compact('imoveis','proprietarios'));

        return $pdf->setPaper('a4')->stream('todos_proprietario.pdf');
    }

    public function PdfInquilinos(){

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->join('proprietario as p', 'im.idproprietario', '=', 'p.idproprietario')
        ->select('im.idimovel','im.nome','p.nome as nomepro','im.endereco')
        ->where('im.condicao','=','Ativo')
        ->get();

        $inquilinos=DB::table('inquilino as i')
        ->join('imovel as im', 'i.idimovel', '=', 'im.idimovel')
        ->select('im.idimovel','im.nome','im.endereco as end_imovel','i.idinquilino','i.nome','i.telefone')
        ->where('i.condicao','=','Ativo')
        ->get();


        $pdf = PDF::loadView('reports/pdf_inquilinos',compact('inquilinos','proprietarios'));

        return $pdf->setPaper('a4')->stream('todos_inquilinos.pdf');
    }

    public function PdfFiadores(){

        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();

        $fiadores=DB::table('fiador as fia')
        ->join('inquilino as i', 'fia.idinquilino', '=', 'i.idinquilino')
        ->select('fia.idfiador','fia.nome','i.nome as nomeinq','fia.endereco','fia.telefone')
        ->where('fia.condicao','=','Ativo')
        ->get();


        $pdf = PDF::loadView('reports/pdf_fiadores',compact('inquilinos','fiadores'));

        return $pdf->setPaper('a4')->stream('todos_fiadores.pdf');
    }

    public function PdfImoveis(){

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        //$imovel=Imovel::all();

        $imoveis=DB::table('imovel as im')
        ->join('proprietario as p', 'im.idproprietario', '=', 'p.idproprietario')
        ->select('im.idimovel','im.nome','p.nome as nomepro','im.endereco')
        ->where('im.condicao','=','Ativo')->get();

        $pdf = PDF::loadView('reports/pdf_imovel',compact('imoveis'));

        return $pdf->setPaper('a4')->stream('todos_imoveis.pdf');
    }


    public function pdfRecibos($id){

        $empresas=DB::table('empresa as e')->get();

        //echo var_dump($id);
        //$id=301; //276; //278

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
        ->select('r.idrecibo','r.mes_ano','r.dt_inicial','r.dt_final','r.total_aluguel','r.valor_pgto',
        'l.idlocacao', 'i.nome as nomeinq','i.idinquilino as codinq','i.cpf_cnpj as cnpjcpfinq',
        'i.telefone as foneinq','p.nome as nomepro', 'p.idproprietario','p.cpf_cnpj as cnpjcpfpro',
        'p.telefone as fonepro' ,'im.idimovel' ,'im.codigo as codigoimo','im.endereco','im.bairro',
        'im.cidade','im.cep','in.nome as nomeind','r.dt_inicial','r.dt_final','r.contador_aluguel',
        'r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.forma_pgto','r.cheque',
        'banco','r.praca','dt_emissao','dt_apresentacao','r.emitente','r.telefone as foneemitente','r.obs')
        ->where('r.idrecibo','=', $id)
        ->get();

        $detalhes=DB::table('detalhe_recibo as d')
        ->join('evento as e','d.idevento','=','e.idevento')
        ->select('e.nome as evento','e.tipo','d.complemento', 'd.qtde', 'd.valor', 'd.mes_ano_det', 'd.qtde_limite')
        ->where('d.idrecibo','=', $id)
        ->get();

        if ($recibos[0]->dt_pagamento) {
            $pdf = PDF::loadView('reports/pdf_recibos',compact('empresas','recibos','detalhes'));
            return $pdf->setPaper('a4')->stream('todos_recibos.pdf');
        }

    }

    public function pdfChaves($id){

        $empresas=DB::table('empresa as e')->get();

        //echo var_dump($id);
        //$id=301; //276; //278

        $inquilinos=DB::table('inquilino as i')
        ->where('i.condicao','=','Ativo')
        ->get();

        $proprietarios=DB::table('proprietario as p')
        ->where('p.condicao','=','Ativo')
        ->get();

        $imoveis=DB::table('imovel as im')
        ->where('im.condicao','=','Ativo')->get();

        $indices=DB::table('indice as ind')->get();

        $locacoes = DB::table('locacao as l')
        ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
        ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
        ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
        ->join('indice as in', 'l.idindice', '=', 'in.idindice')
        ->join('detalhe_locacao as d', 'l.idlocacao', '=', 'd.idlocacao')
        ->select('l.idlocacao','i.nome as nomeinq','i.idinquilino as codinq','p.idproprietario','p.nome as nomepro',
        'im.endereco','im.bairro', 'im.cidade','im.cep','im.idimovel','in.nome as nomeind','dt_inicial',
        'dt_final','reajuste','contador_aluguel','reajuste_sobre',
        'vencimento','taxa_adm','desocupacao' )
        ->where('l.idlocacao','=', $id)
        ->get();


        if ($locacoes[0]->desocupacao) {
            $pdf = PDF::loadView('reports/pdf_chaves',compact('empresas','locacoes'));
            return $pdf->setPaper('a4')->stream('todos_recibos.pdf');
        }

    }


}
