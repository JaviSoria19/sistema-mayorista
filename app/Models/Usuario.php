<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'idUsuario';

    const CREATED_AT = 'fechaRegistro';
    const UPDATED_AT = 'fechaActualizacion';

    /** Relación con empleados */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleado', 'idEmpleado');
    }

    /**Función que retorna todos los registros de la tabla 'usuarios'.*/
    public function getAllUsuarios()
    {
        // Para evitar el problema N+1, conviene cargar también empleado
        return Usuario::with('empleado')->leftJoin('usuarios as u2', 'usuarios.modificadoPor', '=', 'u2.idUsuario')
            ->select('usuarios.*', 'u2.nombreUsuario as modificadoPor')
        ->get();
    }

    /**Función que retorna un objeto del modelo Usuario.*/
    public function getUsuario($idUsuario)
    {
        return Usuario::with('empleado')->find($idUsuario);
    }

    /**Función utilizada para verificar y crear la sesión del Usuario.*/
    public function login($nombreUsuario)
    {
        return Usuario::where('nombreUsuario', $nombreUsuario)->first();
    }

    /**Función para destruir y cerrar la sesión.*/
    public function logout()
    {
        session()->flush();
    }
}
