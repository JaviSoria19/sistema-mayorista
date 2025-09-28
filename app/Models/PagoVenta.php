<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoVenta extends Model
{
    use HasFactory;

    protected $table = 'pagos_ventas';
    protected $primaryKey = 'idPagoVenta';

    const CREATED_AT = 'fechaRegistro';
    const UPDATED_AT = 'fechaActualizacion';

    /** Relación FK con ventas */
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'idVenta', 'idVenta');
    }

    /** Relación con atributo de auditoría */
    public function editor(){
        return $this->belongsTo(Usuario::class, 'modificadoPor', 'idUsuario');
    }

    public function getAllPagosVentas()
    {
        return PagoVenta::with(['venta','editor'])->orderBy('idPagoVenta','ASC')->get();
    }
    
    public function getPagoVenta($idPagoVenta)
    {
        return PagoVenta::with(['venta','editor'])->find($idPagoVenta);
    }
}
