<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    protected $table = 'locacao';
    protected $primaryKey = 'idlocacao';

    // public $timestamps = false;
    protected $fillable = [

        'idinquilino',
        'idproprietario',
        'idimovel',
        'idindice',
        'dt_inicial',
        'dt_final',
        'reajuste',
        'contador_aluguel',
        'reajuste_sobre',
        'vencimento',
        'taxa_adm',
        'desocupacao',
        'estado',
        'mes_ano',
        'dt_ini_contrato',
        'dt_fin_contrato',
        'codigo'

    ];

    protected $guarded = [];

    public function recibos(){
        return $this->hasMany(Recibo::class,'idlocacao');
    }
}
