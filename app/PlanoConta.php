<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanoConta extends Model
{
    protected $table = 'plano_contas';
    protected $primaryKey = 'id';

    protected $fillable = [

        'idempresa',
        'codigo',
        'conta',
        'agrupamento'
];

public function empresa(){
    return $this->belongsTo(Empresa::class, 'idEmpresa');
}

    protected $guarded = [];
}
