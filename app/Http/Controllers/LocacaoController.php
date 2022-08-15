<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Remessa;
use App\Locacao;
use App\DetalheLocacao;
use App\Recibo;
use App\DetalheRecibo;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\LocacaoFormRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class LocacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        $empresas = DB::table('empresa as emp')
            ->get();

        if ($request) {
            $query = trim($request->get('searchText'));
            $locacoes = DB::table('locacao as l')
                ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
                ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
                ->join('indice as in', 'l.idindice', '=', 'in.idindice')
                ->select('l.desocupacao', 'l.idlocacao', 'l.codigo', 'i.idinquilino', 'i.nome as nomeinq',
                 'p.idproprietario', 'p.nome as nomepro', 'im.codigo as codigoimo', 'im.endereco', 
                 'in.nome as nomeind', 'l.dt_inicial', 'l.dt_final', 'l.reajuste', 'l.contador_aluguel', 
                 'l.reajuste_sobre', 'l.vencimento', 'l.taxa_adm', 'l.desocupacao', 'l.mes_ano')
                ->where('l.desocupacao', null)
                ->where('im.codigo', 'LIKE', '%' . $query . '%')
                ->orwhere('im.endereco', 'LIKE', '%' . $query . '%')
                ->orwhere('i.nome', 'LIKE', '%' . $query . '%')
                ->orwhere('p.nome', 'LIKE', '%' . $query . '%')
                ->orwhere('l.idlocacao', 'LIKE', '%' . $query . '%')
                ->orwhere('l.codigo', 'LIKE', '%' . $query . '%')
                ->orwhere('l.mes_ano', 'LIKE', '%' . $query . '%')
                ->where('l.estado', '=', 'Ativo')
                ->orderBy('l.idlocacao', 'desc')
                ->get();
            return view('tabela.locacao.index', [
                "locacoes" => $locacoes,
                "empresas" => $empresas,
                "searchText" => $query
            ]);
        }
    }

    public function create()
    {

        $proprietarios = DB::table('proprietario as p')
            ->where('p.condicao', '=', 'Ativo')
            ->get();

        $imoveis = DB::table('imovel as im')
            ->where('im.condicao', '=', 'Ativo')
            ->get();

        $inquilinos = DB::table('inquilino as i')
            ->join('proprietario as p', 'i.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'i.idimovel', '=', 'im.idimovel')
            ->select('i.idlocacao','i.idinquilino', 'i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco', 'im.situacao')
            ->where('i.condicao', '=', 'Ativo')
            ->where('i.idlocacao','=',  '0')
            ->orwhere('i.idlocacao','=', null)
            ->get();
 
        $indices = DB::table('indice as ind')->get();

        $eventos = DB::table('evento as eve')->get();

        return view("tabela.locacao.create", [
            "inquilinos" => $inquilinos,
            "proprietarios" => $proprietarios,
            "imoveis" => $imoveis,
            "eventos" => $eventos,
            "indices" => $indices
        ]);
    }

    public function store(LocacaoFormRequest $request)
    {

        try {
            DB::beginTransaction();

            $empresas = DB::table('empresa as emp')
                ->get();

            $dadosLocacao = $request->get('idinquilino');
            $array = explode('_', $dadosLocacao);
            $id = $array[0];

            $proprietarios = DB::table('proprietario as p')
                ->where('p.condicao', '=', 'Ativo')
                ->get();

            $imoveis = DB::table('imovel as im')
                ->where('im.condicao', '=', 'Ativo')
                ->get();


            $inquilinos = DB::table('inquilino as i')
                ->join('proprietario as p', 'i.idproprietario', '=', 'p.idproprietario')
                ->join('imovel as im', 'i.idimovel', '=', 'im.idimovel')
                ->select('i.idproprietario', 'i.idimovel', 'i.idinquilino', 'i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco')
                ->where('i.idinquilino', '=', $id)
                ->get();


            $dt_inicial = $request->get('dt_inicial') . ' ' . Carbon::now()->toTimeString();

            $locacao = new locacao;
            $locacao->idinquilino = $inquilinos[0]->idinquilino;
            $locacao->idproprietario = $inquilinos[0]->idproprietario;
            $locacao->idimovel = $inquilinos[0]->idimovel;
            $locacao->idindice = $request->get('idindice');
            $locacao->dt_inicial = $request->get('dt_inicial') . ' ' . Carbon::now()->toTimeString();
            $locacao->dt_final = $request->get('dt_final') . ' ' . Carbon::now()->toTimeString();
            $locacao->reajuste = $request->get('reajuste');
            $locacao->contador_aluguel = $request->get('contador_aluguel');
            $locacao->reajuste_sobre = $request->get('reajuste_sobre');
            $locacao->vencimento = $request->get('vencimento') . ' ' . Carbon::now()->toTimeString();
            $locacao->taxa_adm = $request->get('taxa_adm');
            $locacao->desocupacao = $request->get('desocupacao');
            $locacao->mes_ano = $request->get('mes_ano');
            $locacao->dt_ini_contrato = $request->get('dt_ini_contrato') . ' ' . Carbon::now()->toTimeString();
            $locacao->dt_fin_contrato = $request->get('dt_fin_contrato') . ' ' . Carbon::now()->toTimeString();
            $locacao->codigo = $request->get('codigo');
            $locacao->estado = 'Ativo';
            $locacao->todos_recibos = $request->get('recibos');
            $locacao->save();

            $regImovel = $locacao->idimovel;
            $regInquilino = $locacao->idinquilino;

            $idevento = $request->get('idevento');
            $complemento = $request->get('complemento');
            $qtde = $request->get('qtde');
            $valor = $request->get('valor');
            $mes_ano_det = $request->get('mes_ano_det');
            $qtde_limite = $request->get('qtde_limite');
            $id = $locacao->idlocacao;

            $cont = 0;
            while ($cont < count($idevento)) {
                $detalhe = new detalhelocacao;
                $detalhe->idlocacao = $locacao->idlocacao;
                $detalhe->idevento = $idevento[$cont];
                $detalhe->complemento = $complemento[$cont];
                $detalhe->qtde = $qtde[$cont];
                $detalhe->valor = $valor[$cont];
                $detalhe->mes_ano_det = $mes_ano_det[$cont];
                $detalhe->qtde_limite = $qtde_limite[$cont];
                $detalhe->save();
                $cont = $cont + 1;
            }

            $imovel_up = DB::table('imovel')
                ->where('idimovel', $regImovel)
                ->update([
                    'situacao' => 'Alugado',
                    'idinquilino' => $locacao->idinquilino,
                    'idlocacao' => $locacao->idlocacao
                ]);

            $inquilino_up = DB::table('inquilino')
                ->where('idinquilino', $regInquilino)
                ->update([
                    'idlocacao' => $locacao->idlocacao
                ]);

            /////////////////////Gera Recibo\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
            //$geraTodosBoletos = $empresas[0]->gera_todos_boletos; //"Sim";
            $geraTodosBoletos =  $locacao->todos_recibos;

           // dd($geraTodosBoletos,$locacao);

            $mes_reajuste = $locacao->reajuste;
            $contador_aluguel = $locacao->contador_aluguel;

            $detalhes_locacao = DB::table('detalhe_locacao as d')
                ->join('evento as e', 'd.idevento', '=', 'e.idevento')
                ->select('d.idlocacao', 'd.idevento', 'e.nome as evento', 'd.complemento', 'd.qtde', 'd.qtde_limite', 'd.valor', 'd.mes_ano_det')
                ->where('d.idlocacao', '=', $id)
                ->get();

            //dd($geraTodosBoletos);

            if ($geraTodosBoletos = "Nao") {
                $contador = 1;
                $dtinicial =  Carbon::create($locacao->dt_inicial);
                $dtfinal =  Carbon::create($locacao->dt_final);
                $dtVento =  Carbon::create($locacao->vencimento);
                $dia_dtinicial = substr($dtinicial, 8, 2);
                $dia_dt_final = substr($dtfinal, 8, 2);
                $dia_dt_Vento = substr($dtVento, 8, 2);

                $dia = "";
                $mes = "";
                $ano = "";
                $value =  $dtVento;
                $dia = substr($value, 8, 2);
                $mes = substr($value, 5, 2);
                $ano = substr($value, 0, 4);

                //--------------Data Inicial
                $dia = "";
                $mes = "";
                $ano = "";
                $value =  $dtinicial;
                $dia = $dia_dtinicial; // substr($value, 8, 2);
                $mes = substr($value, 5, 2);
                $ano = substr($value, 0, 4);
                //$mes=$mes+1;
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

                $dtinicial = Carbon::create($ano, $mes, $dia, 0, 0)->format('Y-m-d') . ' ' . Carbon::now()->toTimeString();

                //--------------Data Final
                $dia = "";
                $mes = "";
                $ano = "";
                $value =  $dtfinal;
                $dia = $dia_dt_final; //substr($value, 8, 2);
                $mes = substr($value, 5, 2);
                $ano = substr($value, 0, 4);
                //$mes=$mes+1;
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

                $dtfinal = Carbon::create($ano, $mes, $dia, 0, 0)->format('Y-m-d') . ' ' . Carbon::now()->toTimeString();

                //--------------Data Vencimento
                $dia = "";
                $mes = "";
                $ano = "";
                $value =  $dtVento;
                $dia = $dia_dt_Vento; //substr($value, 8, 2);
                $mes = substr($value, 5, 2);
                $ano = substr($value, 0, 4);
                //$mes=$mes+1;
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

                $dtVento = Carbon::create($ano, $mes, $dia)->format('Y-m-d') . ' ' . Carbon::now()->toTimeString();

                $mes_ano = ($mes . '/' . $ano);

                try {
                    DB::beginTransaction();
                    $recibo = new recibo;
                    $recibo->idlocacao = $locacao->idlocacao;
                    $recibo->idinquilino = $locacao->idinquilino;
                    $recibo->idproprietario = $locacao->idproprietario;
                    $recibo->idimovel = $locacao->idimovel;
                    $recibo->idindice = $locacao->idindice;
                    $recibo->dt_inicial = $dtinicial;
                    $recibo->dt_final = $dtfinal;
                    $recibo->reajuste = $locacao->reajuste;
                    $recibo->contador_aluguel = $contador_aluguel; // $contador;
                    $recibo->dt_vencimento = $dtVento;
                    $recibo->total_aluguel = $locacao->reajuste_sobre;
                    $recibo->mes_ano = $mes_ano;
                    $recibo->codigo = $locacao->codigo;
                    $recibo->estado = 'Ativo';

                    $taxa = $locacao->taxa_adm / 100;
                    $vlrTaxa = $locacao->reajuste_sobre * $taxa;
                    $liquido = $locacao->reajuste_sobre - $vlrTaxa;
                    $total_aluguel = $locacao->reajuste_sobre;

                    $recibo->taxa_adm = $vlrTaxa;
                    $recibo->liquido = $liquido;
                    $recibo->save();

                    $id_Recibo = $recibo->idrecibo;

                    foreach ($detalhes_locacao as $det) {
                        $detalhe = new detalherecibo;
                        $detalhe->idrecibo = $id_Recibo;
                        $detalhe->idevento = $det->idevento;
                        $detalhe->complemento = $det->complemento;
                        $detalhe->qtde = $det->qtde;
                        $detalhe->valor = $det->valor;
                        $detalhe->mes_ano_det = $mes_ano;
                        $detalhe->qtde_limite = $det->qtde_limite;
                        $detalhe->save();
                    }

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
                        ->where('i.idinquilino', '=', $locacao->idinquilino)
                        ->get();

                    $proprietarios = DB::table('proprietario as p')
                        ->where('p.idproprietario', '=', $locacao->idproprietario)
                        ->get();

                    // dd($inquilinos[0]->fisica_juridica,$proprietarios[0]->fisica_juridica);

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
                    }

                   //if ($inquilinos[0]->fisica_juridica == 'Juridica' and $proprietarios[0]->fisica_juridica == 'Fisica') {
                        $recibo = recibo::findOrFail($id_Recibo);
                        $recibo->total_aluguel = $saldoRecalculo;
                        $recibo->update();
                    //}

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                }
                $contador++;
            }; //if ($geraTodosBoletos=="Nao") {

            if ($geraTodosBoletos == "Sim") {
                $contador = 1;
                $dtinicial =  Carbon::create($locacao->dt_inicial);
                $dtfinal =  Carbon::create($locacao->dt_final);
                $dtVento =  Carbon::create($locacao->vencimento);
                $dia_dtinicial = substr($dtinicial, 8, 2);
                $dia_dt_final = substr($dtfinal, 8, 2);
                $dia_dt_Vento = substr($dtVento, 8, 2);

                while ($contador <= $mes_reajuste) {

                    //echo "O contador agora e: " . $contador . "  /  ".$mes_reajuste." Dt ini".$dtinicial." Dt Final".$dtfinal." Dt Vecto".$dtVento."</br>" ;
                    //echo  "Dia data inicial: " . $dia_dtinicial . "  / dia data final: ".$dia_dt_final." Dia vecto:".$dia_dt_Vento."</br>" ;

                    $dia = "";
                    $mes = "";
                    $ano = "";
                    $value =  $dtVento;
                    $dia = substr($value, 8, 2);
                    $mes = substr($value, 5, 2);
                    $ano = substr($value, 0, 4);

                    if ($contador > 1) {
                        //--------------Data Inicial
                        $dia = "";
                        $mes = "";
                        $ano = "";
                        $value =  $dtinicial;
                        $dia = $dia_dtinicial; // substr($value, 8, 2);
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

                        $dtinicial = Carbon::create($ano, $mes, $dia, 0, 0)->format('Y-m-d') . ' ' . Carbon::now()->toTimeString();


                        //--------------Data Final
                        $dia = "";
                        $mes = "";
                        $ano = "";
                        $value =  $dtfinal;
                        $dia = $dia_dt_final; //substr($value, 8, 2);
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

                        $dtfinal = Carbon::create($ano, $mes, $dia, 0, 0)->format('Y-m-d') . ' ' . Carbon::now()->toTimeString();

                        //--------------Data Vencimento
                        $dia = "";
                        $mes = "";
                        $ano = "";
                        $value =  $dtVento;
                        $dia = $dia_dt_Vento; //substr($value, 8, 2);
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
                    }

                    $dtVento = Carbon::create($ano, $mes, $dia)->format('Y-m-d') . ' ' . Carbon::now()->toTimeString();
                    $mes_ano = ($mes . '/' . $ano);

                    try {
                        DB::beginTransaction();
                        $recibo = new recibo;
                        $recibo->idlocacao = $locacao->idlocacao;
                        $recibo->idinquilino = $locacao->idinquilino;
                        $recibo->idproprietario = $locacao->idproprietario;
                        $recibo->idimovel = $locacao->idimovel;
                        $recibo->idindice = $locacao->idindice;
                        $recibo->dt_inicial = $dtinicial;
                        $recibo->dt_final = $dtfinal;
                        $recibo->reajuste = $locacao->reajuste;
                        $recibo->contador_aluguel = $contador;
                        $recibo->dt_vencimento = $dtVento;
                        $recibo->total_aluguel = $locacao->reajuste_sobre;
                        $recibo->mes_ano = $mes_ano;
                        $recibo->codigo = $locacao->codigo;
                        $recibo->estado = 'Ativo';

                        $taxa = $locacao->taxa_adm / 100;
                        $vlrTaxa = $locacao->reajuste_sobre * $taxa;
                        $liquido = $locacao->reajuste_sobre - $vlrTaxa;
                        $recibo->taxa_adm = $vlrTaxa;
                        $recibo->liquido = $liquido;
                        $recibo->save();
                        $id_Recibo = $recibo->idrecibo;

                        foreach ($detalhes_locacao as $det) {
                            $detalhe = new detalherecibo;
                            $detalhe->idrecibo = $id_Recibo;
                            $detalhe->idevento = $det->idevento;
                            $detalhe->complemento = $det->complemento;
                            $detalhe->qtde = $det->qtde;
                            $detalhe->valor = $det->valor;
                            $detalhe->mes_ano_det = $mes_ano;
                            $detalhe->qtde_limite = $det->qtde_limite;
                            $detalhe->save();
                        }

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                    }
                    $contador++;
                } //If ($contador >1){ while

                    $locacao_up = DB::table('locacao')
                    ->where('idlocacao', $id)
                    ->update([
                        'contador_aluguel' => $contador - 1,
                        //'dt_inicial' => $recibo->dt_inicial,
                        //'dt_final' => $recibo->dt_final,
                        'mes_ano' => $recibo->mes_ano,
                        //'vencimento' => $recibo->dt_vencimento
                    ]);
    
            } //if ($geraTodosBoletos=="Sim") {


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return Redirect::to('tabela/locacao');
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

        $detalhes = DB::table('detalhe_locacao as d')
            ->join('evento as e', 'd.idevento', '=', 'e.idevento')
            ->select('e.nome as evento', 'd.complemento', 'd.qtde', 'd.valor', 'd.mes_ano_det')
            ->where('d.idlocacao', '=', $id)
            ->get();

        return view(
            "tabela/locacao.show",
            ["locacao" => $locacao, "detalhe_locacao" => $detalhes]
        );
    }

    public function edit($id)
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


        $locacoes = DB::table('locacao as l')
            ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'l.idindice', '=', 'in.idindice')
            ->join('detalhe_locacao as d', 'l.idlocacao', '=', 'd.idlocacao')
            ->select(
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
                'l.vencimento',
                'l.taxa_adm',
                'l.desocupacao'
            )
            ->where('l.idlocacao', '=', $id)
            ->first();


        $detalhes = DB::table('detalhe_locacao as d')
            ->join('evento as e', 'd.idevento', '=', 'e.idevento')
            ->select('e.nome as evento', 'e.tipo', 'd.iddetalhe_locacao', 'd.complemento', 'd.qtde', 'd.valor', 'd.mes_ano_det', 'd.qtde_limite')
            ->where('d.idlocacao', '=', $id)
            ->get();

        $total_aluguel = 0;
        foreach ($detalhes as $det) {
            if ($det->tipo == 'Credito') {
                $total_aluguel = $total_aluguel + $det->valor;
            } else {
                $total_aluguel = $total_aluguel - $det->valor;
            }
        }
        //$total_aluguel=$detalhes->sum('valor');
        //dd($detalhes,$total_aluguel);
        //die();

        $recibos = DB::table('recibo as r')
            ->join('locacao as l', 'r.idlocacao', '=', 'l.idlocacao')
            ->join('inquilino as i', 'l.idinquilino', '=', 'i.idinquilino')
            ->join('proprietario as p', 'l.idproprietario', '=', 'p.idproprietario')
            ->join('imovel as im', 'l.idimovel', '=', 'im.idimovel')
            ->join('indice as in', 'l.idindice', '=', 'in.idindice')
            ->select('r.idrecibo', 'r.mes_ano', 'l.idlocacao', 'i.nome as nomeinq', 'p.nome as nomepro', 'im.endereco', 'in.nome as nomeind', 'r.dt_inicial', 'r.dt_final', 'r.contador_aluguel', 'r.reajuste', 'l.reajuste_sobre', 'r.dt_vencimento', 'r.dt_pagamento', 'r.total_aluguel', 'l.taxa_adm', 'r.valor_pgto')
            ->where('r.idlocacao', '=', $id)
            ->orderBy('r.idrecibo', 'desc')
            ->get();


        return view(
            "tabela/locacao.edit",
            [
                "locacao" => locacao::findOrFail($id),
                "inquilinos" => $inquilinos,
                "proprietarios" => $proprietarios,
                "imoveis" => $imoveis,
                "eventos" => $eventos,
                "locacoes" => $locacoes,
                "indices" => $indices,
                "recibos" => $recibos,
                "total_eventos" => $total_aluguel,
                "detalhes" => $detalhes
            ]
        );
    }


    public function update(LocacaoFormRequest $request, $id)
    {

        $locacao = locacao::findOrFail($id);
        //$locacao->idinquilino=$request->get('idinquilino');
        //$locacao->idproprietario=$request->get('idproprietario');
        //$locacao->idimovel=$request->get('idimovel');
        //$locacao->idindice=$request->get('idindice');
        $locacao->dt_inicial = $this->parseDate($request->get('dt_inicial')) . ' ' . Carbon::now()->toTimeString();
        $locacao->dt_final = $this->parseDate($request->get('dt_final')) . ' ' . Carbon::now()->toTimeString();
        $locacao->reajuste = $request->get('reajuste');
        $locacao->contador_aluguel = $request->get('contador_aluguel');
        $locacao->reajuste_sobre = $request->get('reajuste_sobre');
        $locacao->vencimento = $this->parseDate($request->get('vencimento')) . ' ' . Carbon::now()->toTimeString();
        $locacao->taxa_adm = $request->get('taxa_adm');
        $locacao->desocupacao = $request->get('desocupacao');
        $locacao->mes_ano = $request->get('mes_ano');
        $locacao->dt_ini_contrato = $this->parseDate($request->get('dt_ini_contrato')) . ' ' . Carbon::now()->toTimeString();
        $locacao->dt_fin_contrato = $this->parseDate($request->get('dt_fin_contrato')) . ' ' . Carbon::now()->toTimeString();
        $locacao->codigo = $request->get('codigo');
        $locacao->update();

        //dd($request,$data);
        //die();


        $idevento=$request->get('idevento');
        $complemento=$request->get('complemento');
        $qtde=$request->get('qtde');
        $qtde_limite=$request->get('qtde_limite');
        $valor=$request->get('valor');
        $mes_ano_det=$request->get('mes_ano_det');
        //dd($idevento);
        //die();
        $cont = 0;
        if ($idevento == 1) {
            return Redirect::back();
        } else {
            while ($cont < count($idevento)) {

                $detalhe = new detalhelocacao;
                //$detalhe=detalhelocacao::findOrFail($id);
                $detalhe->idlocacao=$locacao->idlocacao;
                $detalhe->idevento=$idevento[$cont];
                $detalhe->complemento=$complemento[$cont];
                $detalhe->qtde=$qtde[$cont];
                $detalhe->qtde_limite=$qtde_limite[$cont];
                $detalhe->valor=$valor[$cont];
                $detalhe->mes_ano_det=$mes_ano_det[$cont];
                $detalhe->save();
                $cont = $cont + 1;
            }
            return Redirect::back();
        }
    }


    public function destroy($id)
    {

        $inquilinos = DB::table('inquilino as i')
            ->get();

        $imoveis = DB::table('imovel as im')
            ->get();


        $mytime = Carbon::now();
        $locacao = locacao::findOrFail($id);
        $locacao->estado = 'Inativo';
        $locacao->desocupacao = $mytime; //->toDateTimeString();
        $locacao->update();

        $regimovel = $locacao->idimovel;
        $regInquilino = $locacao->idinquilino;
        $regLocacao = $locacao->idlocacao;

        $imovel_up = DB::table('imovel')
            ->where('idimovel', $regimovel)
            ->update([
                //'idproprietario' => '1',
                'idinquilino' => '0',
                'idlocacao' => '0',
                'situacao' => 'Vago'
            ]);

        $inquilino_up = DB::table('inquilino')
            ->where('idinquilino', $regInquilino)
            ->update([
                'idproprietario' => '1',
                'idimovel' => '1',
                'idlocacao' => '0',
                'condicao' => 'Inativo'
            ]);

        $recibo_up = DB::table('recibo as r')
            ->where('r.idlocacao', '=', $regLocacao)
            ->where('r.dt_pagamento', '=', NULL)
            ->get();

        $idrecibo = $recibo_up[0]->idrecibo;
        $detalhes_recibo = DB::table('detalhe_recibo as d')
            ->where('d.idrecibo', '=', $idrecibo)
            ->get();

        //dd($recibo_up, $detalhes_recibo,$id);

        //Apagando Detalhe do Recibo
        foreach ($detalhes_recibo as $det) {
            $idRecibo = $det->iddetalhe_recibo;
            $detalhe_rec = detalherecibo::findOrFail($idRecibo);
            $detalhe_rec->delete();
        }

        // Apagando do Recibo
        foreach ($recibo_up as $rec) {
            $idRecibo = $rec->idrecibo;

            $remessa = remessa::findOrFail($rec->idremessa);
            $remessa->delete();

            $recibo = recibo::findOrFail($idRecibo);
            $recibo->delete();

        }

        return Redirect::to('tabela/locacao');
    }

    public function apagardetalhe($id)
    {
        $detalhe = detalhelocacao::findOrFail($id);
        $detalhe->delete();
        return Redirect::back();
    }

    private function parseDate($date, $plusDay = false)
    {
        if ($plusDay == false)
            return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
        else
            return date('Y-m-d', strtotime("+1 day", strtotime(str_replace("/", "-", $date))));
    }
}
