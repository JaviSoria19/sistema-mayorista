<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'idEmpleado';

    const CREATED_AT = 'fechaRegistro';
    const UPDATED_AT = 'fechaActualizacion';
    
    public function editor(){
        return $this->belongsTo(Usuario::class, 'modificadoPor', 'idUsuario');
    }

    /**Función que retorna todos los registros de la tabla 'empleados'.*/
    public function getAllEmpleados()
    {
        return Empleado::with('editor')->orderBy('idEmpleado','ASC')->get();
    }

    /**Función que retorna un objeto del modelo Empleado.*/
    public function getEmpleado($idEmpleado)
    {
        return Empleado::with('editor')->find($idEmpleado);
    }
}