<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{
    protected $table = 'bairros';
    protected $primaryKey = 'id';

    protected $fillable = [
    	'cidade_id',
    	'nome',
    ];

    protected $guarded = [];
}
