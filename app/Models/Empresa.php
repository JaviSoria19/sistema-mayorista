<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';
    protected $primaryKey = 'idEmpresa';

    const CREATED_AT = 'fechaRegistro';
    const UPDATED_AT = 'fechaActualizacion';
    
    /** Relación con atributo de auditoría */
    public function editor(){
        return $this->belongsTo(Usuario::class, 'modificadoPor', 'idUsuario');
    }

    public function getAllEmpresas()
    {
        return Empresa::with('editor')->orderBy('idEmpresa','ASC')->get();
    }
    
    public function getEmpresa($idEmpresa)
    {
        return Empresa::with('editor')->find($idEmpresa);
    }
}