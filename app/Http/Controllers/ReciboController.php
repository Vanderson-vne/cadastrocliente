<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Recibo;
use App\DetalheRecibo;
use App\Reajuste;
use App\Tabela_ir;
use App\Locacao;
use App\DetalheLocacao;
use App\Mov_Contas;
use App\Movimentacao;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ReciboFormRequest;
use App\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ReciboController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasAnyRole('Admin', 'Gerente', 'Caixa')) {
            $empresas = DB::table('empresa as emp')
                ->get();

            $inquilinos = DB::table('inquilino as i')
                ->where('i.condicao', '=', 'Ativo')
                ->get();

            $proprietarios = DB::table('proprietario as p')
                ->where('p.condicao', '=', 'Ativo')
                ->get();

            $imoveis = DB::table('imovel as im')
                ->where('im.condicao', '=', 'Ativo')->get();

            $indices = DB::table('indice as ind')->get();

            $data =  Carbon::now();
            $dia = substr($data, 8, 2);
            $mes = substr($data, 5, 2);
            $ano = substr($data, 0, 4);
            $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
            $mes_ano = $mes . "/" . $ano;
            $dtPgto = $ano . "-" . $mes . "-" . $dia ." 00:00:00";
            $dtPgto1 = $ano . "-" . $mes . "-" . $dia ." 23:00:00";

            $results = DB::select('SELECT idevento, SUM(valor) AS "valor"
            FROM movimentacaos
            GROUP BY idevento');

            //dd($mes_ano);
            $dtInicial = $dtInicial = '2001-01-01';
            $dtFinal = $data = date('Y-m-d');

            $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'r.idindice', '=', 'in.idindice')
            ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
            ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
            ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
            ->orderBy('r.dt_vencimento', 'desc')
            ->get();

            $alugueisatrasados = $recibos->sum('total_aluguel');


            $dtFinal = $dtFinal = '2099-01-01';
            $dtInicial = $dtInicial = date('Y-m-d');

            $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'r.idindice', '=', 'in.idindice')
            ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
            ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
            ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
            ->orderBy('r.dt_vencimento', 'desc')
            ->get();

            $alugueisavencer = $recibos->sum('total_aluguel');

            $dtFinal = $dtFinal = date('Y-m-d');
            $dtInicial = $dtInicial = date('Y-m-d');

            $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'r.idindice', '=', 'in.idindice')
            ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
            ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
            ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
            ->orderBy('r.dt_vencimento', 'desc')
            ->get();

            $alugueisavencerdia = $recibos->sum('total_aluguel');

            $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'r.idindice', '=', 'in.idindice')
            ->select('r.idrecibo', 'r.mes_ano', 'l.idlocacao', 'i.nome as nomeinq',
             'i.idinquilino as codinq', 'i.telefone', 'p.nome as nomepro', 'im.codigo as codigoimo', 
             'im.endereco', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 
             'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento','r.dt_pagamento', 'r.forma_pgto', 
             'r.total_aluguel')
            ->where('r.dt_pagamento', '>=',  $dtPgto)
            ->where('r.dt_pagamento', '<=',  $dtPgto1)
            ->get();

            $alugueispagodia = $recibos->sum('total_aluguel');

            if ($request) {
                $query = trim($request->get('searchText'));
                $recibos = DB::table('recibo as r')
                    ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                    ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
                    ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
                    ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
                    ->join('indice as in', 'l.idindice', '=', 'in.idindice')
                    //->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
                    ->select(
                        'r.estado',
                        'r.total_aluguel',
                        'r.idrecibo',
                        'r.codigo',
                        'r.mes_ano',
                        'l.idlocacao',
                        'i.idinquilino',
                        'i.nome as nomeinq',
                        'p.idproprietario',
                        'p.nome as nomepro',
                        'im.codigo as codigoimo',
                        'im.endereco',
                        'in.nome as nomeind',
                        'r.idremessa',
                        'r.dt_inicial',
                        'r.dt_final',
                        'r.contador_aluguel',
                        'r.reajuste',
                        'l.reajuste_sobre',
                        'r.dt_vencimento',
                        'r.dt_pagamento',
                        'r.forma_pgto',
                        'r.valor_pgto'
                    )
                    ->where('r.dt_pagamento', '=', NULL)
                    ->where('r.dt_vencimento', '<=', $data)
                    ->orderBy('r.idrecibo', 'desc')
                    ->get();

                    //dd($recibos);

                $recibosbx = DB::table('recibo as r')
                    ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                    ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
                    ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
                    ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
                    ->join('indice as in', 'l.idindice', '=', 'in.idindice')
                    //->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
                    ->select(
                        'r.estado',
                        'r.total_aluguel',
                        'r.idrecibo',
                        'r.codigo',
                        'r.mes_ano',
                        'l.idlocacao',
                        'i.idinquilino',
                        'i.nome as nomeinq',
                        'p.idproprietario',
                        'p.nome as nomepro',
                        'im.codigo as codigoimo',
                        'im.endereco',
                        'in.nome as nomeind',
                        'r.idremessa',
                        'r.dt_inicial',
                        'r.dt_final',
                        'r.contador_aluguel',
                        'r.reajuste',
                        'l.reajuste_sobre',
                        'r.dt_vencimento',
                        'r.dt_pagamento',
                        'r.forma_pgto',
                        'r.valor_pgto'
                    )
                    ->where('r.mes_ano', 'LIKE', '%' . $query . '%')
                    ->where('r.dt_pagamento', '!=', null)
                    ->orderBy('r.idrecibo', 'desc')
                    ->get();

               // dd($recibos,$recibosbx);

                return view('tabela.recibo.index', [
                    "recibos" => $recibos,
                    "recibosbx" => $recibosbx,
                    "empresas" => $empresas,
                    "alugueisatrasados" => $alugueisatrasados,
                    "alugueisavencer" => $alugueisavencer,
                    "alugueisavencerdia" => $alugueisavencerdia,
                    "alugueispagodia" => $alugueispagodia,
                    "results" => $results,
                    "searchText" => $query
                ]);
            }
        }
        if ($user->hasAnyRole('Inquilino')) {
            $empresas = DB::table('empresa as emp')
                ->get();

            $userid = $user->id;
            $inquilinos = DB::table('inquilino as i')
                ->where('i.condicao', '=', 'Ativo')
                ->where('i.user_id', '=', $userid)
                ->get();

            $idinquilino = $inquilinos[0]->idinquilino;

            $proprietarios = DB::table('proprietario as p')
                ->where('p.condicao', '=', 'Ativo')
                ->get();

            $imoveis = DB::table('imovel as im')
                ->where('im.condicao', '=', 'Ativo')->get();

            $indices = DB::table('indice as ind')->get();

            $data =  Carbon::now();
            $dia = substr($data, 8, 2);
            $mes = substr($data, 5, 2);
            $ano = substr($data, 0, 4);
            $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
            $mes_ano = $mes . "/" . $ano;

            $results = DB::select('SELECT idevento, SUM(valor) AS "valor"
            FROM movimentacaos
            GROUP BY idevento');

            //dd($mes_ano);
            $dtInicial = $dtInicial = '2001-01-01';
            $dtFinal = $dtFinal = date('Y-m-d');

            $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'r.idindice', '=', 'in.idindice')
            ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
            ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
            ->where('r.idinquilino', '=', $idinquilino)
            ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
            ->orderBy('r.dt_vencimento', 'desc')
            ->get();

            $alugueisatrasados = $recibos->sum('total_aluguel');

            $dtFinal = $dtFinal = '2099-01-01';
            $dtInicial = $dtInicial = date('Y-m-d');

            $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'r.idindice', '=', 'in.idindice')
            ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
            ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
            ->where('r.idinquilino', '=', $idinquilino)
            ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
            ->orderBy('r.dt_vencimento', 'desc')
            ->get();

            $alugueisavencer = $recibos->sum('total_aluguel');

            $dtFinal = $dtFinal = date('Y-m-d');
            $dtInicial = $dtInicial = date('Y-m-d');

            $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'r.idindice', '=', 'in.idindice')
            ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
            ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
            ->where('r.idinquilino', '=', $idinquilino)
            ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
            ->orderBy('r.dt_vencimento', 'desc')
            ->get();

            $alugueisavencerdia = $recibos->sum('total_aluguel');

            $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'r.idindice', '=', 'in.idindice')
            ->select('r.idrecibo', 'r.mes_ano', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.telefone', 'p.nome as nomepro', 'im.codigo as codigoimo', 'im.endereco', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.forma_pgto', 'r.total_aluguel')
            ->where('r.dt_pagamento', '!=', Null)
            ->where('r.idinquilino', '=', $idinquilino)
            ->whereBetween('r.dt_pagamento', [$dtInicial, $dtFinal])
            ->orderBy('r.dt_pagamento', 'asc')
            ->get();

            $alugueispagodia = $recibos->sum('total_aluguel');

            if ($request) {
                $query = trim($request->get('searchText'));
                $recibos = DB::table('recibo as r')
                    ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                    ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
                    ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
                    ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
                    ->join('indice as in', 'l.idindice', '=', 'in.idindice')
                    //->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
                    ->select(
                        'r.estado',
                        'r.total_aluguel',
                        'r.idrecibo',
                        'r.codigo',
                        'r.mes_ano',
                        'l.idlocacao',
                        'i.idinquilino',
                        'i.nome as nomeinq',
                        'p.idproprietario',
                        'p.nome as nomepro',
                        'im.codigo as codigoimo',
                        'im.endereco',
                        'in.nome as nomeind',
                        'r.idremessa',
                        'r.dt_inicial',
                        'r.dt_final',
                        'r.contador_aluguel',
                        'r.reajuste',
                        'l.reajuste_sobre',
                        'r.dt_vencimento',
                        'r.dt_pagamento',
                        'r.forma_pgto',
                        'r.valor_pgto'
                    )
                    ->where('r.dt_pagamento', '=', NULL)
                    //->where('r.dt_vencimento', '<=', $data)
                    ->where('r.idinquilino', '=', $idinquilino)
                    ->orderBy('r.idrecibo', 'desc')
                    ->get();

                    //dd($recibos);

                $recibosbx = DB::table('recibo as r')
                    ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                    ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
                    ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
                    ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
                    ->join('indice as in', 'l.idindice', '=', 'in.idindice')
                    //->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
                    ->select(
                        'r.estado',
                        'r.total_aluguel',
                        'r.idrecibo',
                        'r.codigo',
                        'r.mes_ano',
                        'l.idlocacao',
                        'i.idinquilino',
                        'i.nome as nomeinq',
                        'p.idproprietario',
                        'p.nome as nomepro',
                        'im.codigo as codigoimo',
                        'im.endereco',
                        'in.nome as nomeind',
                        'r.idremessa',
                        'r.dt_inicial',
                        'r.dt_final',
                        'r.contador_aluguel',
                        'r.reajuste',
                        'l.reajuste_sobre',
                        'r.dt_vencimento',
                        'r.dt_pagamento',
                        'r.forma_pgto',
                        'r.valor_pgto'
                    )
                    ->where('r.idinquilino', '=', $idinquilino)
                    ->where('r.dt_pagamento', '!=', null)
                    ->orderBy('r.idrecibo', 'desc')
                    ->get();

                return view('tabela.recibo.indexinquilino', [
                    "recibos" => $recibos,
                    "data" => $data,
                    "recibosbx" => $recibosbx,
                    "empresas" => $empresas,
                    "alugueisatrasados" => $alugueisatrasados,
                    "alugueisavencer" => $alugueisavencer,
                    "alugueisavencerdia" => $alugueisavencerdia,
                    "alugueispagodia" => $alugueispagodia,
                    "results" => $results,
                    "searchText" => $query
                ]);
            }
        }
        if ($user->hasAnyRole('Proprietario')) {
            $empresas = DB::table('empresa as emp')
                ->get();

            $userid = $user->id;
            $inquilinos = DB::table('inquilino as i')
                ->where('i.condicao', '=', 'Ativo')
                ->get();

            $proprietarios = DB::table('proprietario as p')
                ->where('p.condicao', '=', 'Ativo')
                ->where('p.user_id', '=', $userid)
                ->get();

            $idproprietario = $proprietarios[0]->idproprietario;

            $imoveis = DB::table('imovel as im')
                ->where('im.condicao', '=', 'Ativo')->get();

            $indices = DB::table('indice as ind')->get();

            $data =  Carbon::now();
            $dia = substr($data, 8, 2);
            $mes = substr($data, 5, 2);
            $ano = substr($data, 0, 4);
            $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
            $mes_ano = $mes . "/" . $ano;

            $results = DB::select('SELECT idevento, SUM(valor) AS "valor"
            FROM movimentacaos
            GROUP BY idevento');

            //dd($mes_ano);
            $dtInicial = $dtInicial = '2001-01-01';
            $dtFinal = $dtFinal = date('Y-m-d');

            $recibos = DB::table('recibo as r')
                ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
                ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
                ->where('r.idproprietario', '=', $idproprietario)
                ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
                ->orderBy('r.dt_vencimento', 'desc')
                ->get();

            $alugueisatrasados = $recibos->sum('total_aluguel');

            $dtFinal = $dtFinal = '2099-01-01';
            $dtInicial = $dtInicial = date('Y-m-d');

            $recibos = DB::table('recibo as r')
                ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
                ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
                ->where('r.idproprietario', '=', $idproprietario)
                ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
                ->orderBy('r.dt_vencimento', 'desc')
                ->get();

            $alugueisavencer = $recibos->sum('total_aluguel');

            $dtFinal = $dtFinal = date('Y-m-d');
            $dtInicial = $dtInicial = date('Y-m-d');

            $recibos = DB::table('recibo as r')
                ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
                ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
                ->where('r.idproprietario', '=', $idproprietario)
                ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
                ->orderBy('r.dt_vencimento', 'desc')
                ->get();

            $alugueisavencerdia = $recibos->sum('total_aluguel');

            $recibos = DB::table('recibo as r')
                ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'r.idindice', '=', 'in.idindice')
                ->select('r.idrecibo', 'r.mes_ano', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.telefone', 'p.nome as nomepro', 'im.codigo as codigoimo', 'im.endereco', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.forma_pgto', 'r.total_aluguel')
                ->where('r.dt_pagamento', '!=', Null)
                ->where('r.idproprietario', '=', $idproprietario)
                ->whereBetween('r.dt_pagamento', [$dtInicial, $dtFinal])
                ->orderBy('r.dt_pagamento', 'asc')
                ->get();

            $alugueispagodia = $recibos->sum('total_aluguel');

            if ($request) {
                $query = trim($request->get('searchText'));
                $recibos = DB::table('recibo as r')
                    ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                    ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
                    ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
                    ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
                    ->join('indice as in', 'l.idindice', '=', 'in.idindice')
                    //->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
                    ->select(
                        'r.idproprietario',
                        'r.estado',
                        'r.total_aluguel',
                        'r.idrecibo',
                        'r.codigo',
                        'r.mes_ano',
                        'l.idlocacao',
                        'i.idinquilino',
                        'i.nome as nomeinq',
                        'p.idproprietario',
                        'p.nome as nomepro',
                        'im.codigo as codigoimo',
                        'im.endereco',
                        'in.nome as nomeind',
                        'r.idremessa',
                        'r.dt_inicial',
                        'r.dt_final',
                        'r.contador_aluguel',
                        'r.reajuste',
                        'l.reajuste_sobre',
                        'r.dt_vencimento',
                        'r.dt_pagamento',
                        'r.forma_pgto',
                        'r.valor_pgto'
                    )
                    ->where('r.dt_pagamento', '=', NULL)
                    ->where('r.dt_vencimento', '<=', $data)
                    ->where('r.idproprietario', '=', $idproprietario)
                    ->orderBy('r.idrecibo', 'desc')
                    ->get();

                $recibosbx = DB::table('recibo as r')
                    ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                    ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
                    ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
                    ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
                    ->join('indice as in', 'l.idindice', '=', 'in.idindice')
                    //->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
                    ->select(
                        'r.idproprietario',
                        'r.estado',
                        'r.total_aluguel',
                        'r.idrecibo',
                        'r.codigo',
                        'r.mes_ano',
                        'l.idlocacao',
                        'i.idinquilino',
                        'i.nome as nomeinq',
                        'p.idproprietario',
                        'p.nome as nomepro',
                        'im.codigo as codigoimo',
                        'im.endereco',
                        'in.nome as nomeind',
                        'r.idremessa',
                        'r.dt_inicial',
                        'r.dt_final',
                        'r.contador_aluguel',
                        'r.reajuste',
                        'l.reajuste_sobre',
                        'r.dt_vencimento',
                        'r.dt_pagamento',
                        'r.forma_pgto',
                        'r.valor_pgto'
                    )
                    ->where('r.idproprietario', '=', $idproprietario)
                    ->where('r.dt_pagamento', '!=', null)
                    ->orderBy('r.idrecibo', 'desc')
                    ->get();

                return view('tabela.recibo.indexproprietario', [
                    "recibos" => $recibos,
                    "recibosbx" => $recibosbx,
                    "empresas" => $empresas,
                    "alugueisatrasados" => $alugueisatrasados,
                    "alugueisavencer" => $alugueisavencer,
                    "alugueisavencerdia" => $alugueisavencerdia,
                    "alugueispagodia" => $alugueispagodia,
                    "results" => $results,
                    "searchText" => $query
                ]);
            }
        }
    }

    public function create(Request $request)
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

        $eventos = DB::table('evento as eve')->get();

        $locacoes = DB::table('locacao as l')
            ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'l.idindice', '=', 'in.idindice')
            ->select('l.idlocacao', 'l.mes_ano', 'i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco', 'in.nome as nomeind', 'l.dt_inicial', 'l.dt_final', 'l.reajuste', 'l.contador_aluguel', 'l.reajuste_sobre', 'l.vencimento')
            ->where('l.estado', '=', 'Ativo')
            ->get();

        if ($request) {
            $query = trim($request->get('searchText'));
            $recibos = DB::table('recibo as r')
                ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'l.idindice', '=', 'in.idindice')
                ->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
                ->select('r.idrecibo', 'r.mes_ano', 'l.idlocacao', 'i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'r.dt_vencimento', 'r.dt_pagamento')
                ->where('r.mes_ano', 'LIKE', '%' . $query . '%')
                //->where('im.endereco', 'LIKE', '%'.$query.'%')
                ->orwhere('i.nome', 'LIKE', '%' . $query . '%')
                ->orwhere('p.nome', 'LIKE', '%' . $query . '%')
                ->orderBy('r.idrecibo', 'desc')
                ->get();
        }

        return view("tabela.recibo.create", [
            "empresa" => $empresa,
            "inquilinos" => $inquilinos,
            "proprietarios" => $proprietarios,
            "imoveis" => $imoveis,
            "eventos" => $eventos,
            "locacoes" => $locacoes,
            "recibos" => $recibos,
            "searchText" => $query,
            "indices" => $indices
        ]);
    }

    public function store(ReciboFormRequest $request)
    {
        $inquilinos = DB::table('inquilino as i')
            ->where('i.condicao', '=', 'Ativo')
            ->get();

        $proprietarios = DB::table('proprietario as p')
            ->where('p.condicao', '=', 'Ativo')
            ->get();

        $imoveis = DB::table('imovel as im')
            ->where('im.condicao', '=', 'Ativo')->get();

        $indices = DB::table('indice as ind')->get();

        $eventos = DB::table('evento as eve')->get();

        $mes_ano = $request->get('mes_ano');
        $dadosLocacao = $request->get('idlocacao');
        $array = explode('_', $dadosLocacao);
        $id = $array[0];


        if ($id == "Todas") {
            $locacoes = DB::table('locacao as l')
                ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'l.idindice', '=', 'in.idindice')
                ->select('l.idlocacao', 'l.idinquilino', 'l.idproprietario', 'l.idimovel', 'l.idindice', 'l.codigo', 'l.mes_ano', 'i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco', 'in.nome as nomeind', 'l.dt_inicial', 'l.dt_final', 'l.reajuste', 'l.contador_aluguel', 'l.reajuste_sobre', 'l.vencimento', 'l.taxa_adm', 'mes_ano', 'l.estado')
                ->where('l.mes_ano', '=', $mes_ano)
                ->where('l.estado', '=', 'Ativo')
                ->get();
        } else {
            $locacoes = DB::table('locacao as l')
                ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'l.idindice', '=', 'in.idindice')
                ->select('l.idlocacao', 'l.idinquilino', 'l.idproprietario', 'l.idimovel', 'l.idindice', 'l.codigo', 'l.mes_ano', 'i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco', 'in.nome as nomeind', 'l.dt_inicial', 'l.dt_final', 'l.reajuste', 'l.contador_aluguel', 'l.reajuste_sobre', 'l.vencimento', 'l.taxa_adm', 'mes_ano', 'l.estado')
                ->where('l.mes_ano', '=', $mes_ano)
                ->where('l.estado', '=', 'Ativo')
                ->where('l.idlocacao', '=', $id)
                ->get();
        }

        /////////////////////////////////NOVO RECIBO\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        //dd('store');
        foreach ($locacoes as $loc) {

            $id = $loc->idlocacao;
            $idindice = $loc->idindice;

            $reajustes = DB::table('reajuste as r')
                ->where('r.idindice', '=', $idindice)
                ->where('r.mes_ano', '=', $mes_ano)
                ->get();

            $detalhes_locacao = DB::table('detalhe_locacao as d')
                ->join('evento as e', 'd.idevento', '=', 'e.idevento')
                ->select('d.idlocacao', 'd.idevento', 'e.nome as evento', 'd.complemento', 'd.qtde', 'd.qtde_limite', 'd.valor', 'd.mes_ano_det')
                ->where('d.idlocacao', '=', $id)
                ->get();

           // dd($locacoes, $loc, $id);

            $idinquilino = $loc->idinquilino;
            $recibos = DB::table('recibo as r')
                ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
                ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'l.idindice', '=', 'in.idindice')
                ->select(
                    'r.idrecibo',
                    'l.idlocacao',
                    'r.idinquilino',
                    'i.nome as nomeinq',
                    'p.nome as nomepro',
                    'im.endereco',
                    'in.nome as nomeind',
                    'l.dt_inicial',
                    'l.dt_final',
                    'l.reajuste',
                    'l.contador_aluguel',
                    'l.reajuste_sobre',
                    'l.vencimento',
                    'r.mes_ano',
                    'r.dt_pagamento'
                )
                ->where('r.idinquilino', '=', $idinquilino)
                ->where('r.mes_ano', '=', $mes_ano)
                ->get();


            if ($recibos->isEmpty()) { //Not Achei
               // dd('Not Achei');
            } else { //Achei
               // dd('Achei');
            }

            //dd($locacoes, $recibos, $loc, $id);

            //try {
            //	DB::beginTransaction();
            $recibo = new recibo;
            $recibo->idlocacao = $loc->idlocacao;
            $recibo->idinquilino = $loc->idinquilino;
            $recibo->idproprietario = $loc->idproprietario;
            $recibo->idimovel = $loc->idimovel;
            $recibo->idindice = $loc->idindice;
            $regloc = $loc->idlocacao;

            //--------------Data Inicial
            //$data = Carbon::create($loc->dt_inicial);
            //$data->addDays(30);
            $dia = "";
            $mes = "";
            $ano = "";
            $value =  Carbon::create($loc->dt_inicial);
            $dia = substr($value, 8, 2);
            $mes = substr($value, 5, 2);
            $ano = substr($value, 0, 4);
            $mes = $mes + 1;
            if ($mes == 13) {
                $mes = 1;
                $ano = $ano + 1;
            }
            $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);

            $data = Carbon::create($ano, $mes, $dia, 0, 0);
            $recibo->dt_inicial = $data;

            //--------------Data Final
            $dia = "";
            $mes = "";
            $ano = "";
            $value =  Carbon::create($loc->dt_final);
            $dia = substr($value, 8, 2);
            $mes = substr($value, 5, 2);
            $ano = substr($value, 0, 4);
            $mes = $mes + 1;
            if ($mes == 13) {
                $mes = 1;
                $ano = $ano + 1;
            }
            $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);

            $data = Carbon::create($ano, $mes, $dia, 0, 0);
            $recibo->dt_final = $data;

            $recibo->reajuste = $loc->reajuste;

            $contador = $loc->contador_aluguel + 1;
            $recibo->contador_aluguel = $contador;

            //--------------Data Vencimento
            $dia = "";
            $mes = "";
            $ano = "";
            $value =  Carbon::create($loc->vencimento);
            $dia = substr($value, 8, 2);
            $mes = substr($value, 5, 2);
            $ano = substr($value, 0, 4);
            $mes = $mes + 1;
            if ($mes == 13) {
                $mes = 1;
                $ano = $ano + 1;
            }
            $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
            $mes_ano = ($mes . '/' . $ano);
            $data = Carbon::create($ano, $mes, $dia, 0, 0);
            $recibo->dt_vencimento = $data;

            $mes_reajuste = $loc->reajuste;
            $contador_aluguel = $loc->contador_aluguel;
            $total_aluguel = $loc->reajuste_sobre;

            $recibo->total_aluguel = $loc->reajuste_sobre;
            $taxa = $loc->taxa_adm / 100;
            $vlrTaxa = $loc->reajuste_sobre * $taxa;
            $liquido = $loc->reajuste_sobre - $vlrTaxa;
            $recibo->taxa_adm = $vlrTaxa;
            $recibo->liquido = $liquido;

            $recibo->codigo = $loc->codigo;
            $recibo->mes_ano = $mes_ano;
            $recibo->estado = 'Ativo';
            $recibo->save();
            $id_Recibo = $recibo->idrecibo;

            foreach ($detalhes_locacao as $det) {
                $detalhe = new detalherecibo;
                $detalhe->idrecibo = $id_Recibo;
                $detalhe->idevento = $det->idevento;
                $detalhe->complemento = $det->complemento;
                $detalhe->qtde = $det->qtde;
                $detalhe->valor = $det->valor;
                $detalhe->mes_ano_det = $det->mes_ano_det;
                $detalhe->qtde_limite = $det->qtde_limite;
                $detalhe->save();
            }

            if ($mes_reajuste = $contador_aluguel) {
                $contador = 1;
                if ($mes_reajuste == '1') {
                    $percentual = ($reajustes[0]->mensal / 100) + 1;
                }
                if ($mes_reajuste == '2') {
                    $percentual = ($reajustes[0]->bimestral / 100) + 1;
                }
                if ($mes_reajuste == '3') {
                    $percentual = ($reajustes[0]->trimestral / 100) + 1;
                }
                if ($mes_reajuste == '4') {
                    $percentual = ($reajustes[0]->quadrimestral / 100) + 1;
                }
                if ($mes_reajuste == '5') {
                    $percentual = ($reajustes[0]->quintimestral / 100) + 1;
                }
                if ($mes_reajuste == '6') {
                    $percentual = ($reajustes[0]->semestral / 100) + 1;
                }
                if ($mes_reajuste == '12') {
                    $percentual = ($reajustes[0]->anual / 100) + 1;
                }
                if ($mes_reajuste == '24') {
                    $percentual = ($reajustes[0]->bianual / 100) + 1;
                }
                $total_aluguel = ($total_aluguel * $percentual);
            }

            $locacao_up = DB::table('locacao')
                ->where('idlocacao', $regloc)
                ->update([
                    'contador_aluguel' => $contador,
                    'dt_inicial' => $recibo->dt_inicial,
                    'dt_final' => $recibo->dt_final,
                    'mes_ano' => $recibo->mes_ano,
                    'vencimento' => $recibo->dt_vencimento,
                    'reajuste_sobre' => $total_aluguel
                ]);

            //    		DB::commit();
            //} catch (\Exception $e) {
            // DB::rollback();
            //	}

        } //foreach

        return Redirect::to('tabela/recibo');
    }

    public function show($id)
    {
        $locacao = DB::table('locacao as l')
            ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'l.idindice', '=', 'in.idindice')
            ->join('detalhe_locacao as d', 'l.idlocacao', '=', 'd.idlocacao')
            ->select('l.idlocacao', 'i.nome', 'p.nome', 'im.endereco', 'in.nome', 'dt_inicial', 'dt_final', 'reajuste', 'contador_aluguel', 'reajuste_sobre', 'vencimento', 'taxa_adm', 'desocupacao')
            ->where('l.idlocacao', '=', $id)
            ->first();

        $detalhes = DB::table('detalhe_recibo as d')
            ->join('evento as e', 'd.idevento', '=', 'e.idevento')
            ->select('e.nome as evento', 'd.complemento', 'd.qtde', 'd.valor', 'd.mes_ano')
            ->where('d.idlocacao', '=', $id)
            ->get();

        return view(
            "tabela/locacao.show",
            ["locacao" => $locacao, "detalhe_recibo" => $detalhes]
        );
    }

    public function edit($id)
    {
        $recibo = recibo::findOrFail($id);
        $idLoc = $recibo->idlocacao;

        $inquilinos = DB::table('inquilino as i')
            ->where('i.condicao', '=', 'Ativo')
            ->get();

        $proprietarios = DB::table('proprietario as p')
            ->where('p.condicao', '=', 'Ativo')
            ->get();

        $imoveis = DB::table('imovel as im')
            ->where('im.condicao', '=', 'Ativo')->get();

        $indices = DB::table('indice as ind')->get();

        $eventos = DB::table('evento as eve')->get();

        $locacoes = DB::table('locacao as l')
            ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'l.idindice', '=', 'in.idindice')
            ->select(
                'l.mes_ano',
                'l.idindice',
                'l.idlocacao',
                'i.nome as nomeinq',
                'p.nome as nomepro',
                'im.endereco',
                'in.nome as nomeind',
                'l.dt_inicial',
                'l.dt_final',
                'l.reajuste',
                'l.contador_aluguel',
                'l.reajuste_sobre',
                'l.vencimento'
            )
            ->where('l.idlocacao', '=', $idLoc)
            ->get();

        ////////////Valores\\\\\\\\\\\\\\\\\
        $mes_reajuste = $locacoes[0]->reajuste;
        $contador_aluguel = $locacoes[0]->contador_aluguel;
        $total_aluguel = $locacoes[0]->reajuste_sobre;
        $total_aluguelLoc = $locacoes[0]->reajuste_sobre;

        $idindice = $locacoes[0]->idindice;
        $mes_ano = $locacoes[0]->mes_ano;

        //--------------Achar a Tabela de Reajuste
        $mes = "";
        $ano = "";
        $value =  $mes_ano;
        $mes = substr($value, 0, 2);
        $ano = substr($value, 3, 4);
        $mes = $mes + 1;
        $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $mes_ano = ($mes . '/' . $ano);

        $reajustes = DB::table('reajuste as r')
            ->where('r.idindice', '=', $idindice)
            ->where('r.mes_ano', '=', $mes_ano)
            ->get();

        $contador = $locacoes[0]->contador_aluguel;

        //dd($recibo->contador_aluguel,$recibo->reajuste);

        //if ($mes_reajuste == $contador) {
        if ($recibo->contador_aluguel == $recibo->reajuste) {

            $contador = 1;
            if ($reajustes->isEmpty()) {
                return redirect()->route('recibo.index')
                    ->with('danger', 'ATENÇÃO !  Tabela de Reajuste não encontrada ...Recibo Nº ' . "  " . $id . " - " . $mes_ano);
            } else {
            }
        }

        $detalhes = DB::table('detalhe_recibo as d')
            ->join('evento as e', 'd.idevento', '=', 'e.idevento')
            ->select('e.nome as evento', 'e.tipo', 'd.iddetalhe_recibo', 'd.complemento', 'd.qtde', 'd.valor', 'd.mes_ano_det', 'd.qtde_limite', 'd.idevento')
            ->where('d.idrecibo', '=', $id)
            ->get();

        $total_aluguel = 0;
        foreach ($detalhes as $det) {
            if ($det->tipo == 'Credito') {
                $total_aluguel = $total_aluguel + $det->valor;
            } else {
                $total_aluguel = $total_aluguel - $det->valor;
            }
        }

        //dd($detalhes,$total_aluguel);
        //die();

        $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'l.idindice', '=', 'in.idindice')
            ->select(
                'r.idrecibo',
                'l.idlocacao',
                'i.nome as nomeinq',
                'p.nome as nomepro',
                'im.endereco',
                'in.nome as nomeind',
                'l.dt_inicial',
                'l.dt_final',
                'l.reajuste',
                'l.contador_aluguel',
                'l.reajuste_sobre',
                'l.vencimento'
            )
            ->where('r.idrecibo', '=', $id)
            ->get();

        //dd($recibos);

        return view(
            "tabela.recibo.edit",
            [
                "recibo" => recibo::findOrFail($id),
                "inquilinos" => $inquilinos,
                "proprietarios" => $proprietarios,
                "imoveis" => $imoveis,
                "eventos" => $eventos,
                "indices" => $indices,
                "recibos" => $recibos,
                "locacoes" => $locacoes,
                "detalhes" => $detalhes,
                "total_eventos" => $total_aluguel
            ]
        );
    }

    public function update(ReciboFormRequest $request, $id)
    {
        $empresas = DB::table('empresa as emp')
            ->get();

        $eventos = DB::table('evento as eve')
            ->get();

        $mov_contas = DB::table('mov_contas as mov')
            ->get();


        try {
            DB::beginTransaction();
            $recibo = recibo::findOrFail($id);
            $idLoc = $recibo->idlocacao;
            $estado = $recibo->estado;

            $locacao = DB::table('locacao as l')
            ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'l.idindice', '=', 'in.idindice')
            ->select(
                'l.idlocacao',
                'l.idinquilino',
                'l.idproprietario',
                'l.idimovel',
                'l.idindice',
                'l.codigo',
                'l.mes_ano',
                'i.nome as nomeinq',
                'p.nome as nomepro',
                'im.endereco',
                'in.nome as nomeind',
                'l.dt_inicial',
                'l.dt_final',
                'l.dt_fin_contrato',
                'l.reajuste',
                'l.contador_aluguel',
                'l.reajuste_sobre',
                'l.vencimento',
                'l.taxa_adm',
                'l.todos_recibos',
                'mes_ano',
                'l.estado'
            )
            //->where('l.estado','=','Ativo')
            ->where('l.idlocacao', '=', $idLoc)
            ->get();

            $idindice = $locacao[0]->idindice;
            $mes_ano = $locacao[0]->mes_ano;

            //$geraTodosBoletos = $empresas[0]->gera_todos_boletos;
            $geraTodosBoletos = $locacao[0]->todos_recibos;
            if ($geraTodosBoletos==null) {
                $geraTodosBoletos = 'Nao';
            }

            //--------------Achar a Tabela de Reajuste
            $mes = "";
            $ano = "";
            $value =  $mes_ano;
            $mes = substr($value, 0, 2);
            $ano = substr($value, 3, 4);
            $mes = $mes + 1;
            $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
            $value_mes_ano = ($mes . '/' . $ano);

            $reajustes = DB::table('reajuste as r')
            ->where('r.idindice', '=', $idindice)
            ->where('r.mes_ano', '=', $value_mes_ano)
            ->get();

            $recibo->dt_pagamento = $request->get('dt_pagamento') . ' ' . Carbon::now()->toTimeString();
            $recibo->valor_pgto = $request->get('valor_pgto');
            $recibo->troco = $request->get('troco');
            $recibo->forma_pgto = $request->get('forma_pgto');
            $recibo->cheque = $request->get('cheque');
            $recibo->banco = $request->get('banco');
            $recibo->praca = $request->get('praca');
            $recibo->dt_emissao = $request->get('dt_emissao');
            $recibo->dt_apresentacao = $request->get('dt_apresentacao');
            $recibo->emitente = $request->get('emitente');
            $recibo->telefone = $request->get('telefone');
            $recibo->obs = $request->get('obs');
            $recibo->total_aluguel = $locacao[0]->reajuste_sobre;
            $taxa = $locacao[0]->taxa_adm / 100;
            $vlrTaxa = $locacao[0]->reajuste_sobre * $taxa;
            $liquido = $locacao[0]->reajuste_sobre - $vlrTaxa;
            $recibo->taxa_adm = $vlrTaxa;
            $recibo->liquido = $liquido;
            $recibo->estado = 'Ativo';
            $recibo->update();

            $idrecibo = $recibo->idrecibo;
            $idevento = $request->get('idevento');
            $complemento = $request->get('complemento');
            $qtde = $request->get('qtde');
            $valor = $request->get('valor');
            $mes_ano_det = $request->get('mes_ano_det');
            $historico = ($locacao[0]->nomeinq . " " . $recibo->forma_pgto . " " . $recibo->cheque . " " . $recibo->obs);

            ///////////Gera Movimentação de Caixa
            if ($recibo->dt_pagamento != 0)
            {
                $bb = $empresas[0]->conta_caixa;
                $bancos = DB::table('banco as ban')
                    ->where('ban.idbanco', '=', $bb)
                    ->get();

                $trans = $empresas[0]->transacao_caixa;
                $transacoes = DB::table('transacao as tra')
                    ->where('tra.idtransacao', '=', $trans)
                    ->get();

                $comp = "Nao";
                $valor = $recibo->valor_pgto;

                if ($transacoes[0]->tipo == "Credito") {
                    $total_saldo = $bancos[0]->saldo + $valor;
                }
                if ($transacoes[0]->tipo == "Debito") {
                    $total_saldo = $bancos[0]->saldo - $valor;
                }

                $mov_banco = new Mov_Contas;
                $mov_banco->idempresa = $empresas[0]->idempresa;
                $mov_banco->idbanco = $bb;
                $mov_banco->idrecibo = $recibo->idrecibo;
                $mov_banco->idtransacao = $trans;
                $mov_banco->data = $recibo->dt_pagamento;
                $mov_banco->documento = $recibo->idrecibo;
                $mov_banco->valor = $recibo->valor_pgto;
                $mov_banco->historico = $historico;
                $mov_banco->compensado = $comp;
                $mov_banco->idhistorico = 1;
                $mov_banco->parcial = $total_saldo;
                $mov_banco->save();

                $banco_up = DB::table('banco')
                    ->where('idbanco', $bb)
                    ->update(['saldo' => $total_saldo,]);
            } //if ($recibo->dt_pagamento != 0) {

            $cont = 0;
            if ($idevento != 1) {
                while ($cont < count($idevento)) {
                    $Detalhe = new DetalheRecibo;
                    $Detalhe->idrecibo = $recibo->idrecibo;
                    $Detalhe->idevento = $idevento[$cont];
                    $Detalhe->complemento = $complemento[$cont];
                    $Detalhe->qtde = $qtde[$cont];
                    $Detalhe->valor = $valor[$cont];
                    $Detalhe->mes_ano_det = $mes_ano_det[$cont];
                    $Detalhe->save();
                    $cont = $cont + 1;
                }
            }

            ///////////////GERA MOVIMENTO DOS LANÇAMENTOS
            $detalhes_recibo = DB::table('detalhe_recibo as d')
            ->where('d.idrecibo', '=', $idrecibo)
            ->get();

            ///////////////////////////Codigo de Ajuste no Detalhe da Locação
           $reci = recibo::findOrFail($id);
           $loca = Locacao::findOrFail($reci->idlocacao);
           $detales_loca = DetalheLocacao::where('idlocacao', $loca->idlocacao)->get();
           $numeloop = 0;

           foreach ($detales_loca as $detalher) {
            if ($detalher->idevento != 1) {
                if ($detalher->qtde != null) {
                    if($detalher->qtde == $detalher->qtde_limite){
                        $detalher->delete();
                    }
                }
               }
            $numeloop++;
            }
            $detales_loca = DetalheLocacao::where('idlocacao', $loca->idlocacao)->get();
            $numeloop = 0;

            foreach ($detales_loca as $detalher) {
                if ($detalher->idevento != 1) {
                    if ($detalher->qtde != null) {
                            $detalher->qtde = $detalher->qtde + 1;
                            $detalher->update();
                    }
                }
            $numeloop++;
            }
            ///////////////////////////Codigo de Ajuste no Detalhe da Locação


            foreach ($detalhes_recibo as $det)
            {
                $eventos = DB::table('evento as env')
                ->where('env.idevento', '=', $det->idevento)
                ->get();

                $valor = $det->valor;
                $taxa_adm = $locacao[0]->taxa_adm / 100;

                $movimentacao = new Movimentacao;
                $movimentacao->idempresa = $empresas[0]->idempresa;
                $movimentacao->idinquilino = $recibo->idinquilino;
                $movimentacao->idproprietario = $recibo->idproprietario;
                $movimentacao->idlocacao = $recibo->idlocacao;
                $movimentacao->idrecibo = $idrecibo;
                $movimentacao->idevento = $det->idevento;
                $movimentacao->incide_conta_cor = $eventos[0]->indice_cc;
                $movimentacao->Tipo_D_C = $eventos[0]->tipo;
                $movimentacao->complemento = $det->complemento;
                $movimentacao->data = $recibo->dt_pagamento;
                $movimentacao->documento = $recibo->idrecibo;
                $movimentacao->mes_ano = $recibo->mes_ano;
                $movimentacao->valor = $det->valor;
                $movimentacao->historico = $historico;
                $movimentacao->incide_caixa = 'Sim';
                $movimentacao->caixa_rec_pag = 'Receita';
                $movimentacao->tipo_lacto = 'Recibo';
                $movimentacao->idbanco = $empresas[0]->conta_caixa;
                if ($eventos[0]->comissao == 'Sim') {
                    $movimentacao->comissao = $valor * $taxa_adm;
                }
                $movimentacao->save();
            }

            /////////////////////////////////NOVO RECIBO\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
            //$id=$loc->idlocacao;

            $detalhes_locacao = DB::table('detalhe_locacao as d')
            ->join('evento as e', 'd.idevento', '=', 'e.idevento')
            ->select('d.idlocacao', 'd.idevento', 'e.nome as evento', 'd.complemento', 'd.qtde', 'd.qtde_limite', 'd.valor', 'd.mes_ano_det')
            ->where('d.idlocacao', '=', $idLoc)
            ->get();

            if ($estado == 'Estorno') {
                $geraTodosBoletos = 'Estorno';
            }

            //Trecho de Código que atualiza a Qtde em Locacao//
            $reci = recibo::findOrFail($id);
            $loca = Locacao::findOrFail($reci->idlocacao);
            $detales_loca = DetalheLocacao::where('idlocacao', $loca->idlocacao)->get();
            $numeloop = 0;
            //Final
            if ($geraTodosBoletos == "Nao") {
                try
                {
                    DB::beginTransaction();
                    $recibo = new recibo;
                    $recibo->idlocacao = $locacao[0]->idlocacao;
                    $recibo->idinquilino = $locacao[0]->idinquilino;
                    $recibo->idproprietario = $locacao[0]->idproprietario;
                    $recibo->idimovel = $locacao[0]->idimovel;
                    $recibo->idindice = $locacao[0]->idindice;
                    $regloc = $locacao[0]->idlocacao;

                    //--------------Data Inicial
                    $dia = "";
                    $mes = "";
                    $ano = "";
                    $value =  Carbon::create($locacao[0]->dt_inicial);
                    $dia = substr($value, 8, 2);
                    $mes = substr($value, 5, 2);
                    $ano = substr($value, 0, 4);
                    $mes = $mes + 1;
                    if ($mes == 13) {
                        $mes = 1;
                        $ano = $ano + 1;
                    }
                    $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
                    if ($mes == '02') {
                        if ($dia > 28) {
                            $dia = 28;
                        }
                    }
                    if ($mes == '04') {
                        if ($dia > 30) {
                            $dia = 30;
                        }
                    }
                    if ($mes == '06') {
                        if ($dia > 30) {
                            $dia = 30;
                        }
                    }
                    if ($mes == '09') {
                        if ($dia > 30) {
                            $dia = 30;
                        }
                    }
                    if ($mes == '11') {
                        if ($dia > 30) {
                            $dia = 30;
                        }
                    }


                    $data = Carbon::create($ano, $mes, $dia, 0, 0)->format('Y-m-d') . ' ' . Carbon::now()->toTimeString();
                    $recibo->dt_inicial = $data;

                    //--------------Data Final
                    $dia = "";
                    $mes = "";
                    $ano = "";
                    $dtContrato =  Carbon::create($locacao[0]->dt_fin_contrato);
                    $value =  Carbon::create($locacao[0]->dt_final);
                    //$dia = substr($value, 8, 2);
                    $dia = substr($dtContrato, 8, 2);
                    $mes = substr($value, 5, 2);
                    $ano = substr($value, 0, 4);
                    $mes = $mes + 1;
                    if ($mes == 13) {
                        $mes = 1;
                        $ano = $ano + 1;
                    }
                    $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
                    if ($mes == '02') {
                        if ($dia > 28) {
                            $dia = 28;
                        }
                    }
                    if ($mes == '04') {
                        if ($dia > 30) {
                            $dia = 30;
                        }
                    }
                    if ($mes == '06') {
                        if ($dia > 30) {
                            $dia = 30;
                        }
                    }
                    if ($mes == '09') {
                        if ($dia > 30) {
                            $dia = 30;
                        }
                    }
                    if ($mes == '11') {
                        if ($dia > 30) {
                            $dia = 30;
                        }
                    }

                    $data = Carbon::create($ano, $mes, $dia, 0, 0)->format('Y-m-d') . ' ' . Carbon::now()->toTimeString();
                    $recibo->dt_final = $data;

                    //--------------Data Vencimento
                    $dia = "";
                    $mes = "";
                    $ano = "";
                    $value =  Carbon::create($locacao[0]->vencimento);
                    $dia = substr($value, 8, 2);
                    $mes = substr($value, 5, 2);
                    $ano = substr($value, 0, 4);
                    $mes = $mes + 1;
                    if ($mes == 13) {
                        $mes = 1;
                        $ano = $ano + 1;
                    }

                    $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
                    if ($mes == '02') {
                        if ($dia > 28) {
                            $dia = 28;
                        }
                    }
                    if ($mes == '04') {
                        if ($dia > 30) {
                            $dia = 30;
                        }
                    }
                    if ($mes == '06') {
                        if ($dia > 30) {
                            $dia = 30;
                        }
                    }
                    if ($mes == '09') {
                        if ($dia > 30) {
                            $dia = 30;
                        }
                    }
                    if ($mes == '11') {
                        if ($dia > 30) {
                            $dia = 30;
                        }
                    }

                    //$mes=str_pad($mes, 2, '0',STR_PAD_LEFT);
                    $mes_ano = ($mes . '/' . $ano);
                    $data = Carbon::create($ano, $mes, $dia, 0, 0)->format('Y-m-d') . ' ' . Carbon::now()->toTimeString();

                    $recibo->dt_vencimento = $data;
                    $recibo->mes_ano = $mes_ano;

                    ////////////Valores\\\\\\\\\\\\\\\\\
                    $mes_reajuste = $locacao[0]->reajuste;
                    $contador_aluguel = $locacao[0]->contador_aluguel;
                    $total_aluguel = $locacao[0]->reajuste_sobre;
                    $contador = $locacao[0]->contador_aluguel + 1;

                    if ($mes_reajuste == $contador_aluguel) {

                        $contador = 1;
                        $percentual = 1;

                        if ($reajustes->isEmpty()) {
                            $percentual = 1;
                        } else {
                            if ($mes_reajuste == '1') {
                                $percentual = ($reajustes[0]->mensal / 100) + 1;
                            }
                            if ($mes_reajuste == '2') {
                                $percentual = ($reajustes[0]->bimestral / 100) + 1;
                            }
                            if ($mes_reajuste == '3') {
                                $percentual = ($reajustes[0]->trimestral / 100) + 1;
                            }
                            if ($mes_reajuste == '4') {
                                $percentual = ($reajustes[0]->quadrimestral / 100) + 1;
                            }
                            if ($mes_reajuste == '5') {
                                $percentual = ($reajustes[0]->quintimestral / 100) + 1;
                            }
                            if ($mes_reajuste == '6') {
                                $percentual = ($reajustes[0]->semestral / 100) + 1;
                            }
                            if ($mes_reajuste == '12') {
                                $percentual = ($reajustes[0]->anual / 100) + 1;
                            }

                            if ($mes_reajuste == '24') {
                                $percentual = ($reajustes[0]->bianual / 100) + 1;
                            }
                        }
                        $total_aluguel = ($total_aluguel * $percentual);
                    }
                    $recibo->reajuste = $locacao[0]->reajuste;
                    $recibo->contador_aluguel = $contador;
                    $recibo->codigo = $locacao[0]->codigo;

                    $recibo->total_aluguel = $total_aluguel;
                    $taxa = $locacao[0]->taxa_adm / 100;
                    $vlrTaxa = $locacao[0]->reajuste_sobre * $taxa;
                    $liquido = $locacao[0]->reajuste_sobre - $vlrTaxa;
                    $recibo->taxa_adm = $vlrTaxa;
                    $recibo->liquido = $liquido;
                    $recibo->estado = 'Ativo';
                    $recibo->save();
                    $id_Recibo = $recibo->idrecibo;

                    foreach ($detales_loca as $det) {
                        $detalhe = new detalherecibo;
                        $detalhe->idrecibo = $id_Recibo;
                        $detalhe->idevento = $det->idevento;
                        $detalhe->complemento = $det->complemento;
                        $detalhe->qtde = $det->qtde;
                        $detalhe->valor = $det->valor;
                        if ($det->idevento == '1') {
                            $detalhe->valor = $total_aluguel;
                        }
                        $detalhe->mes_ano_det = $det->mes_ano_det;
                        $detalhe->qtde_limite = $det->qtde_limite;
                        $detalhe->save();
                    }


                    $det_locacao_up = DB::table('detalhe_locacao')
                        ->where('idevento', '=', '1')
                        ->where('idlocacao', $idLoc)
                        ->update([
                            'valor' => $total_aluguel,
                        ]);

                    ///////////////////Verificação da Tabela de Imposto de Renda
                    $tabela_ir = DB::table('tabela_irs as ir')
                        ->get();

                    $valor_ir = 0;
                    $perc = 0;
                    foreach ($tabela_ir as $ir) {
                        if ($total_aluguel < $ir->faixa1) {
                            $perc = $ir->aliquota1 / 100;
                            $valor_ir = $total_aluguel * $perc;
                            if ($valor_ir >= $ir->deduzir1) {
                                $valor_ir = $valor_ir - $ir->deduzir1;
                            }
                        }
                        if ($total_aluguel > $ir->faixa1 and $total_aluguel <= $ir->faixa2) {
                            $perc = $ir->aliquota2 / 100;
                            $valor_ir = $total_aluguel * $perc;
                            if ($valor_ir >= $ir->deduzir2) {
                                $valor_ir = $valor_ir - $ir->deduzir2;
                            }
                        }
                        if ($total_aluguel > $ir->faixa2 and $total_aluguel <= $ir->faixa3) {
                            $perc = $ir->aliquota3 / 100;
                            $valor_ir = $total_aluguel * $perc;
                            if ($valor_ir >= $ir->deduzir3) {
                                $valor_ir = $valor_ir - $ir->deduzir3;
                            }
                        }
                        if ($total_aluguel > $ir->faixa3 and $total_aluguel <= $ir->faixa4) {
                            $perc = $ir->aliquota4 / 100;
                            $valor_ir = $total_aluguel * $perc;
                            if ($valor_ir >= $ir->deduzir4) {
                                $valor_ir = $valor_ir - $ir->deduzir4;
                            }
                        }
                        if ($total_aluguel > $ir->faixa5) {
                            $perc = $ir->aliquota5 / 100;
                            $valor_ir = $total_aluguel * $perc;
                            if ($valor_ir >= $ir->deduzir5) {
                                $valor_ir = $valor_ir - $ir->deduzir5;
                            }
                        }
                    }

                    $inquilinos = DB::table('inquilino as i')
                        ->where('i.idinquilino', '=', $locacao[0]->idinquilino)
                        ->get();

                    $proprietarios = DB::table('proprietario as p')
                        ->where('p.idproprietario', '=', $locacao[0]->idproprietario)
                        ->get();

                    if ($inquilinos[0]->fisica_juridica == 'Juridica' and $proprietarios[0]->fisica_juridica == 'Fisica') {
                        if ($valor_ir != 0) {
                            $detalhe = new detalherecibo;
                            $detalhe->idrecibo = $id_Recibo;
                            $detalhe->idevento = '4'; //I.R.F.
                            $detalhe->complemento = $det->complemento;
                            $detalhe->qtde = $det->qtde;
                            $detalhe->valor = $valor_ir;
                            $detalhe->mes_ano_det = $det->mes_ano_det;
                            $detalhe->qtde_limite = $det->qtde_limite;
                            $detalhe->save();
                        }
                    }


                    $detalhes_recibo = DB::table('detalhe_recibo as d')
                        ->where('d.idrecibo', '=', $id_Recibo)
                        ->get();

                    $saldoRecalculo = 0;
                    foreach ($detalhes_recibo as $det) {

                        $idevento = $det->idevento;
                        $evento = DB::table('evento as eve')
                            ->where('eve.idevento', '=', $idevento)
                            ->get();

                        if ($evento[0]->tipo == "Credito") {
                            $saldoRecalculo = $det->valor + $saldoRecalculo;
                        }
                        if ($evento[0]->tipo == "Debito") {
                            $saldoRecalculo = $saldoRecalculo - $det->valor;
                        }
                        //    echo "(==============================Evento ==" . $det->idevento . ") " . "Tipo (" . $evento[0]->tipo . ") <br>.";
                        //    echo "(============================Valor===" . $det->valor . ") " . " Acumulado(" . $saldoRecalculo . ") " . "<br>";
                        //    echo "=======================================================";

                    }

                   // if ($inquilinos[0]->fisica_juridica == 'Juridica' and $proprietarios[0]->fisica_juridica == 'Fisica') {
                        $recibo = recibo::findOrFail($id_Recibo);
                        $recibo->total_aluguel = $saldoRecalculo;
                        $recibo->update();
                   // }

                    $locacao_up = DB::table('locacao')
                        ->where('idlocacao', $regloc)
                        ->update([
                            'contador_aluguel' => $contador,
                            'dt_inicial' => $recibo->dt_inicial,
                            'dt_final' => $recibo->dt_final,
                            'mes_ano' => $recibo->mes_ano,
                            'vencimento' => $recibo->dt_vencimento,
                            'reajuste_sobre' => $total_aluguel,
                            'todos_recibos' => $geraTodosBoletos
                        ]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                }
            } //if ($geraTodosBoletos=="Nao") {

            DB::commit();
        }catch (\Exception $e) {
            DB::rollback();
        }

        return Redirect::to('tabela/recibo');
    }

    public function destroy($id)
    {
        $mytime = \Carbon\Carbon::now('America/Sao_Paulo');
        $recibo = recibo::findOrFail($id);
        $valor_pgto = $recibo->valor_pgto;
        $recibo->dt_pagamento = Null;
        $recibo->forma_pgto = Null;
        $recibo->cheque = Null;
        $recibo->banco = Null;
        $recibo->praca = Null;
        $recibo->dt_emissao = Null;
        $recibo->dt_apresentacao = Null;
        $recibo->emitente = Null;
        $recibo->valor_pgto = Null;
        $recibo->troco = Null;
        $recibo->telefone = Null;
        $recibo->obs = Null;
        $recibo->nomeremessa = Null;
        $recibo->idremessa = Null;
        $recibo->estado = 'Estorno';
        $recibo->update();

        $mov_de_contas = DB::table('mov_contas as mc')
            ->where('mc.idrecibo', '=', $id)
            ->get();
        $mc_idbanco = $mov_de_contas[0]->idbanco;

        if ($mov_de_contas->isEmpty()) {
        } else {
            foreach ($mov_de_contas as $mov) {
                $idloop = $mov->idmov_contas;
                $contas = mov_contas::find($idloop);
                $contas->delete();
            }
        }

        $movimentacoes = DB::table('movimentacaos as m')
            ->where('m.idrecibo', '=', $id)
            ->get();

        if ($movimentacoes->isEmpty()) {
        } else {
            foreach ($movimentacoes as $mov) {
                $idloop = $mov->id;
                $mov = movimentacao::find($idloop);
                $mov->delete();
            }
        }

        $bancos = DB::table('banco as ban')
            ->where('ban.idbanco', '=', $mc_idbanco)
            ->get();

        $saldo = $bancos[0]->saldo - $valor_pgto;
        $mc_idbanco = DB::table('banco')
            ->where('idbanco', $mc_idbanco)
            ->update([
                'saldo' => $saldo,
            ]);

        return Redirect::to('tabela/recibo');
    }

    public function apagardetalhe($id)
    {
        $detalhe = DetalheRecibo::findOrFail($id);
        $idrecibo = $detalhe->idrecibo;
        $detalhe->delete();

        $detalhes_recibo = DB::table('detalhe_recibo as d')
            ->where('d.idrecibo', '=', $idrecibo)
            ->get();

        $saldoRecalculo = 0;
        foreach ($detalhes_recibo as $det) {

            $idevento = $det->idevento;
            $evento = DB::table('evento as eve')
                ->where('eve.idevento', '=', $idevento)
                ->get();

            if ($evento[0]->tipo == "Credito") {
                $saldoRecalculo = $det->valor + $saldoRecalculo;
            }
            if ($evento[0]->tipo == "Debito") {
                $saldoRecalculo = $saldoRecalculo - $det->valor;
            }
        }

        // dd($request,$detalhes_recibo,$saldoRecalculo);
        // die();

        $recibo = recibo::findOrFail($idrecibo);
        $recibo->total_aluguel = $saldoRecalculo;
        $recibo->update();

        return Redirect::back();
    }

    public function guardarEvento(Request $request)
    {
        $Detalhe = new DetalheRecibo;
        $Detalhe->idrecibo = $request->idrecibo;
        $Detalhe->idevento = $request->idevento;
        $Detalhe->complemento = $request->complemento;
        $Detalhe->qtde = $request->qtde;
        $Detalhe->qtde_limite = $request->qtde_limite;
        $Detalhe->valor = $request->valor;
        $Detalhe->mes_ano_det = $request->mes_ano_det;
        $Detalhe->save();

        $detalhes_recibo = DB::table('detalhe_recibo as d')
            ->where('d.idrecibo', '=', $request->idrecibo)
            ->get();

        $saldoRecalculo = 0;
        foreach ($detalhes_recibo as $det) {

            $idevento = $det->idevento;
            $evento = DB::table('evento as eve')
                ->where('eve.idevento', '=', $idevento)
                ->get();

            if ($evento[0]->tipo == "Credito") {
                $saldoRecalculo = $det->valor + $saldoRecalculo;
            }
            if ($evento[0]->tipo == "Debito") {
                $saldoRecalculo = $saldoRecalculo - $det->valor;
            }
        }

        $recibo = recibo::findOrFail($request->idrecibo);
        $recibo->total_aluguel = $saldoRecalculo;
        $recibo->update();

        return Redirect::back();
    }

    public function recibosAll()
    {
        $empresas = DB::table('empresa as emp')->get();

        $inquilinos = DB::table('inquilino as i')->where('i.condicao', '=', 'Ativo')->get();

        $proprietarios = DB::table('proprietario as p')
        ->where('p.condicao', '=', 'Ativo')
        ->get();

        $imoveis = DB::table('imovel as im')->where('im.condicao', '=', 'Ativo')->get();

        $indices = DB::table('indice as ind')->get();

        $data =  Carbon::now();
        $dia = substr($data, 8, 2);
        $mes = substr($data, 5, 2);
        $ano = substr($data, 0, 4);
        $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $mes_ano = $mes . "/" . $ano;

        $results = DB::select('SELECT idevento, SUM(valor) AS "valor"
        FROM movimentacaos
        GROUP BY idevento');

        //dd($mes_ano);
        $dtInicial = $dtInicial = '2001-01-01';
        $dtFinal = $dtFinal = date('Y-m-d');

        $recibos = DB::table('recibo as r')
        ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
        ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
        ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
        ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
        ->join('indice as in', 'r.idindice', '=', 'in.idindice')
        ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
        ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
        ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
        ->orderBy('r.dt_vencimento', 'desc')
        ->get();

        $alugueisatrasados = $recibos->sum('total_aluguel');

        $dtFinal = $dtFinal = '2099-01-01';
        $dtInicial = $dtInicial = date('Y-m-d');

        $recibos = DB::table('recibo as r')
        ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
        ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
        ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
        ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
        ->join('indice as in', 'r.idindice', '=', 'in.idindice')
        ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
        ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
        ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
        ->orderBy('r.dt_vencimento', 'desc')
        ->get();

        $alugueisavencer = $recibos->sum('total_aluguel');

        $dtFinal = $dtFinal = date('Y-m-d');
        $dtInicial = $dtInicial = date('Y-m-d');

        $recibos = DB::table('recibo as r')
        ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
        ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
        ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
        ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
        ->join('indice as in', 'r.idindice', '=', 'in.idindice')
        ->select('r.idrecibo', 'r.mes_ano', 'r.codigo', 'r.forma_pgto', 'r.dt_inicial', 'r.dt_final', 'r.total_aluguel', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.cpf_cnpj as cnpjcpfinq', 'i.telefone as foneinq', 'p.nome as nomepro', 'p.idproprietario', 'p.cpf_cnpj as cnpjcpfpro', 'p.telefone as fonepro', 'im.idimovel', 'im.codigo as codigoimo', 'im.endereco', 'im.bairro', 'im.cidade', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.taxa_adm', 'r.liquido', 'r.forma_pgto')
        ->where('r.dt_pagamento', '=', '"2000-01-01 00:00:01"')->orWhereNull('r.dt_pagamento')
        ->whereBetween('r.dt_vencimento', [$dtInicial, $dtFinal])
        ->orderBy('r.dt_vencimento', 'desc')
        ->get();

        $alugueisavencerdia = $recibos->sum('total_aluguel');

        $recibos = DB::table('recibo as r')
        ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
        ->join('inquilino as i', 'r.idinquilino', '=', 'i.idinquilino')
        ->join('proprietario as p', 'r.idproprietario', '=', 'p.idproprietario')
        ->join('imovel as im', 'r.idimovel', '=', 'im.idimovel')
        ->join('indice as in', 'r.idindice', '=', 'in.idindice')
        ->select('r.idrecibo', 'r.mes_ano', 'l.idlocacao', 'i.nome as nomeinq', 'i.idinquilino as codinq', 'i.telefone', 'p.nome as nomepro', 'im.codigo as codigoimo', 'im.endereco', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.forma_pgto', 'r.total_aluguel')
        ->where('r.dt_pagamento', '!=', Null)
        ->whereBetween('r.dt_pagamento', [$dtInicial, $dtFinal])
        ->orderBy('r.dt_pagamento', 'asc')
        ->get();

        $alugueispagodia = $recibos->sum('total_aluguel');


        $recibos = DB::table('recibo as r')
        ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
        ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
        ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
        ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
        ->join('indice as in', 'l.idindice', '=', 'in.idindice')
        //->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
        ->select('r.estado','r.total_aluguel','r.idrecibo','r.codigo','r.mes_ano','l.idlocacao','i.idinquilino','i.nome as nomeinq','p.idproprietario','p.nome as nomepro','im.codigo as codigoimo','im.endereco','in.nome as nomeind','r.idremessa','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.forma_pgto','r.valor_pgto')
        ->where('r.dt_pagamento', '=', NULL)
        // ->where('r.dt_vencimento', '<=', $data)
        ->orderBy('r.idrecibo', 'desc')
        ->get();

        $recibosbx = DB::table('recibo as r')
        ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
        ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
        ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
        ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
        ->join('indice as in', 'l.idindice', '=', 'in.idindice')
        //->join('detalhe_recibo as dr', 'r.idrecibo', '=', 'dr.idrecibo')
        ->select('r.estado','r.total_aluguel','r.idrecibo','r.codigo','r.mes_ano','l.idlocacao','i.idinquilino','i.nome as nomeinq','p.idproprietario','p.nome as nomepro','im.codigo as codigoimo','im.endereco','in.nome as nomeind','r.idremessa','r.dt_inicial','r.dt_final','r.contador_aluguel','r.reajuste','l.reajuste_sobre','r.dt_vencimento','r.dt_pagamento','r.forma_pgto','r.valor_pgto')
        ->where('r.dt_pagamento', '!=', null)
        ->orderBy('r.idrecibo', 'desc')
        ->get();

        //dd($recibos,$recibosbx);

        return view('tabela.recibo.index', [
            "recibos" => $recibos,
            "recibosbx" => $recibosbx,
            "empresas" => $empresas,
            "alugueisatrasados" => $alugueisatrasados,
            "alugueisavencer" => $alugueisavencer,
            "alugueisavencerdia" => $alugueisavencerdia,
            "alugueispagodia" => $alugueispagodia,
            "results" => $results,
            "searchText" => ''
        ]);
    }
}
