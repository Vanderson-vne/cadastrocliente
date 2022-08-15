<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financeiro extends Model
{
    protected $table = 'financeiros';
    protected $primaryKey = 'id';

    protected $fillable = [

		'classificacao_id',
        'pessoa_dupls_id',
        'duplicata',
        'pagar_receber',
        'tipo',
        'nf',
        'data_nf',
        'valor',
        'valor_liquido',
        'pgto_conta',
        'juros',
        'juros_dia',
        'desconto',
        'vencimento',
        'pagamento',
        'nro_cheque',
        'apresentacao',
        'contabil',
        'lote',
        'historico'
    ];

    protected $guarded = [];
}
