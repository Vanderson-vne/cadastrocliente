<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    protected $table = 'transacao';
    protected $primaryKey = 'idtransacao';

    protected $fillable = [

        'idempresa',
        'transacao',
        'tipo',
        'situacao',
        'filial',
        'conta',
        'transacao_filial'
    ];

    protected $guarded = [];
}
