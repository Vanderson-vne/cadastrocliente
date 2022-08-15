<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classificacao extends Model
{
    protected $table = 'classificacaos';
    protected $primaryKey = 'id';

    protected $fillable = [

		'nome',
    'agrupamento',
    'pag_rec'
    ];

    protected $guarded = [];
}
