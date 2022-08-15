<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabela_ir extends Model
{
    protected $table = 'tabela_irs';
    protected $primaryKey = 'id';

    protected $fillable = [

        'idempresa',
        'codigo',
        'faixa1',
        'aliquota1',
        'deduzir1',
        'faixa2',
        'aliquota2',
        'deduzir2',
        'faixa3',
        'aliquota3',
        'deduzir3',
        'faixa4',
        'aliquota4',
        'deduzir4',
        'faixa5',
        'aliquota5',
        'deduzir5',
    ];

    protected $guarded = [];
}
