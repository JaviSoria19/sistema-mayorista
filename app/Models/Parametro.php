<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    use HasFactory;

    protected $table = 'parametros';
    protected $primaryKey = 'idParametro';

    const CREATED_AT = null;
    const UPDATED_AT = null;

    public function editor(){
        return $this->belongsTo(Usuario::class, 'modificadoPor', 'idUsuario');
    }

    /**Función que retorna el objeto del modelo Parametro.*/
    public function getParametro()
    {
        return Parametro::with('editor')->find(1);
    }
}
