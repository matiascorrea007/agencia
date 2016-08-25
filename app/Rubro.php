<?php

namespace agencia;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $fillable = [
        'id',
        'descripcion',
        'created_at',
        'updated_at',
       
    ];
}
