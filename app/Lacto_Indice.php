<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lacto_Indice extends Model
{
    protected $table = 'lacto_indice';
    protected $primaryKey = 'idlacto_indice';

    protected $fillable = [

        'idindice',
        'mes_ano',
        'valor'
    ];

    protected $guarded = [];
}
