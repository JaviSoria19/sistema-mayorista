<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'idCliente';

    const CREATED_AT = 'fechaRegistro';
    const UPDATED_AT = 'fechaActualizacion';
    
    /** Relación con atributo de auditoría */
    public function editor(){
        return $this->belongsTo(Usuario::class, 'modificadoPor', 'idUsuario');
    }
    
    public function getAllClientes()
    {
        return Cliente::with('editor')->orderBy('idCliente','ASC')->get();
    }
    
    public function getCliente($idCliente)
    {
        return Cliente::with('editor')->find($idCliente);
    }
}