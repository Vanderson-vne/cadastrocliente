<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalheRecibo extends Model
{
    protected $table = 'detalhe_recibo';
    protected $primaryKey = 'iddetalhe_recibo';

    protected $fillable = [

        'idrecibo',
        'idevento',
        'complemento',
        'qtde',
        'valor',
        'mes_ano_det',
        'qtde_limite'

    ];

    protected $guarded = [];

    public function recibo(){
        return $this->belongsTo(Recibo::class,'idrecibo');
    }

    public function eventos(){
        return $this->belongsTo(Evento::class,'idevento');
    }
}
