<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoEmpresa extends Model
{
    use HasFactory;

    protected $table = 'saldos_empresas';
    protected $primaryKey = 'idSaldoEmpresa';

    const CREATED_AT = 'fechaRegistro';
    const UPDATED_AT = 'fechaActualizacion';

    /** Relación FK con empresas */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idEmpresa', 'idEmpresa');
    }

    /** Relación con atributo de auditoría */
    public function editor(){
        return $this->belongsTo(Usuario::class, 'modificadoPor', 'idUsuario');
    }

    public function getAllSaldosEmpresas()
    {
        return SaldoEmpresa::with(['empresa','editor'])->orderBy('idSaldoEmpresa','ASC')->get();
    }
    
    public function getSaldoEmpresa($idSaldoEmpresa)
    {
        return SaldoEmpresa::with(['empresa','editor'])->find($idSaldoEmpresa);
    }
}
