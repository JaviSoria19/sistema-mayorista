<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marcas';
    protected $primaryKey = 'idMarca';

    const CREATED_AT = 'fechaRegistro';
    const UPDATED_AT = 'fechaActualizacion';
    
    public function editor(){
        return $this->belongsTo(Usuario::class, 'modificadoPor', 'idUsuario');
    }

    /**Función que retorna todos los registros de la tabla 'empleados'.*/
    public function getAllMarcas()
    {
        return Marca::with('editor')->orderBy('idMarca','ASC')->get();
    }

    /**Función que retorna un objeto del modelo Marca.*/
    public function getMarca($idMarca)
    {
        return Marca::with('editor')->find($idMarca);
    }
}