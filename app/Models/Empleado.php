<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'idEmpleado';

    protected $fillable = [
        'nombreEmpleado',
        'estado',
        'modificadoPor'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fechaRegistro' => 'datetime',
        'fechaActualizacion' => 'datetime',
    ];

    const CREATED_AT = 'fechaRegistro';
    const UPDATED_AT = 'fechaActualizacion';

    public function modificadoPorNombre(){
        return $this->belongsTo(Usuario::class, 'modificadoPor', 'idUsuario');
    }
}