<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reajuste extends Model
{
    protected $table = 'reajuste';
    protected $primaryKey = 'idreajuste';

    protected $fillable = [

        'idindice',
        'mes_ano',
        'mensal',
        'bimestral',
        'trimestral',
        'quadrimestral',
        'quintimestral',
        'semestral',
        'anual',
        'bianual'
    ];

    protected $guarded = [];
}
