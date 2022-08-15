<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $table = 'cidades';
    protected $primaryKey = 'id';

    protected $fillable = [
    	'nome',
    ];

    protected $guarded = [];
}
