<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoricoPadrao extends Model
{
    protected $table = 'historico_padraos';
    protected $primaryKey = 'id';

    protected $fillable = [

    	'idempresa',
    	'codigo',
    	'historico',
    	'agrupamento'
    ];

    protected $guarded = [];
}
