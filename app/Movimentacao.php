<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{
    protected $table = 'movimentacaos';
    protected $primaryKey = 'id';

    protected $fillable = [

        'idempresa',
        'idinquilino',
        'idproprietario',
        'idbanco',
        'idmov_contas',
        'idtransacao',
        'idevento',
        'idlocacao',
        'idrecibo',
        'conta',
        'data',
        'mes_ano',
        'historico',
        'documento',
        'valor',
        'complemento',
        'comissao',
        'incide_caixa',
        'caixa_rec_pag',
        'incide_conta_cor',
        'Tipo_D_C',
        'nominal',
        'predatado',
        'compensado',
        'ult_extrato',
        'parcial',
        'idhistorico'

];

protected $guarded = [];

public function empresa(){
    return $this->belongsTo(Empresa::class, 'idEmpresa');
}

public function inquilino(){
    return $this->belongsTo(Inquilino::class, 'idInquilino');
}

public function proprietario(){
    return $this->belongsTo(Proprietario::class, 'idProprietario');
}

public function eventos(){
    return $this->belongsTo(Evento::class,'idevento');
}

}
