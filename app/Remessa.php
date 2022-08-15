<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remessa extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [

        'id',
        'path_remessa'
    ];
}
