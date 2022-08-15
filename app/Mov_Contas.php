<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mov_Contas extends Model
{
    protected $table = 'mov_contas';
    protected $primaryKey = 'idmov_contas';

    protected $fillable = [

        'idempresa',
        'idbanco',
        'idtransacao',
        'data',
        'documento',
        'historico',
        'compensado',
        'parcial',
        'idhistorico',
        'idrecibo'
    ];

    protected $guarded = [];
}
