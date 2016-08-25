<?php

namespace agencia;

use Illuminate\Database\Eloquent\Model;
use agencia\rubro;
class RubroTipo extends Model
{
     protected $fillable = [
        'id',
        'tipo',
        'rubro_id',
        'created_at',
        'updated_at',
       
    ];
}

Class Rubro extends RubroTipo
{
	//puedes acceder a myFunction
}
