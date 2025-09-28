<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';
    protected $primaryKey = 'idVenta';

    const CREATED_AT = 'fechaRegistro';
    const UPDATED_AT = 'fechaActualizacion';

    /** Relación muchos a muchos con productos */
    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,           // Modelo relacionado
            'detalles_ventas',         // Tabla pivote
            'idVenta',                 // FK en la tabla pivote hacia ventas
            'idProducto'               // FK en la tabla pivote hacia productos
        )->withPivot('precioUSD');     // Campos extras de la tabla pivote
    }

    /** Relación FK con usuarios */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    /** Relación FK con clientes */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'idCliente');
    }

    /** Relación FK con empleados */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleado', 'idEmpleado');
    }

    /** Relación con atributo de auditoría */
    public function editor()
    {
        return $this->belongsTo(Usuario::class, 'modificadoPor', 'idUsuario');
    }

    public function getAllVentas()
    {
        return Venta::with(['productos','usuario', 'cliente', 'empleado', 'editor'])->orderBy('idVenta', 'ASC')->get();
    }

    public function getVenta($idVenta)
    {
        return Venta::with(['productos','usuario', 'cliente', 'empleado', 'editor'])->find($idVenta);
    }
}
