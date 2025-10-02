<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /** Relación uno a muchos con detalles_pedidos_empresas */
    public function pagos()
    {
        return $this->hasMany(PagoVenta::class, 'idVenta', 'idVenta');
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
        return Venta::with(['productos.marca', 'pagos', 'usuario', 'cliente', 'empleado', 'editor'])->orderBy('idVenta', 'ASC')->get();
    }

    public function getVentasPorEstadoYSaldo($estado, $saldoUSD_operador, $saldoUSD, $orden, $fechaInicio, $fechaFin)
    {
        return Venta::with(['productos.marca', 'pagos', 'usuario', 'cliente', 'empleado', 'editor'])
            ->where('estado', $estado)
            ->where('saldoUSD', $saldoUSD_operador, $saldoUSD)
            ->whereBetween('fechaRegistro', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->orderBy('idVenta', $orden)
            ->get();
    }

    public function getVenta($idVenta)
    {
        return Venta::with(['productos.marca', 'pagos', 'usuario', 'cliente', 'empleado', 'editor'])->find($idVenta);
    }

    public function dashboard_getCantidadVentasHoy()
    {
        return Venta::where('estado', 1)
            ->whereDate('fechaRegistro', Carbon::today())
            ->count();
    }

    public function dashboard_getCantidadVentasSemana()
    {
        return Venta::where('estado', 1)
            ->whereBetween('fechaRegistro', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();
    }

    public function dashboard_getCantidadVentasMes()
    {
        return Venta::where('estado', 1)
            ->whereYear('fechaRegistro', Carbon::now()->year)
            ->whereMonth('fechaRegistro', Carbon::now()->month)
            ->count();
    }

    public function dashboard_getIngresosVentasHoy()
    {
        return Venta::where('estado', 1)
            ->whereDate('fechaRegistro', Carbon::today())
            ->select(DB::raw('SUM(totalUSD - saldoUSD) as ingresos'))
            ->value('ingresos');
    }

    public function dashboard_getIngresosVentasSemana()
    {
        return Venta::where('estado', 1)
            ->whereBetween('fechaRegistro', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(DB::raw('SUM(totalUSD - saldoUSD) as ingresos'))
            ->value('ingresos');
    }

    public function dashboard_getIngresosVentasMes()
    {
        return Venta::where('estado', 1)
            ->whereYear('fechaRegistro', Carbon::now()->year)
            ->whereMonth('fechaRegistro', Carbon::now()->month)
            ->select(DB::raw('SUM(totalUSD - saldoUSD) as ingresos'))
            ->value('ingresos');
    }

    public function dashboard_getClientesConSaldo()
    {
        return Venta::select(
            'clientes.idCliente',
            'clientes.nombreCliente',
            'clientes.celular',
            'clientes.procedencia',
            DB::raw('SUM(ventas.saldoUSD) as saldoPendiente'),
            DB::raw('MIN(ventas.fechaRegistro) as fechaMasAntigua')
        )
            ->join('clientes', 'ventas.idCliente', '=', 'clientes.idCliente')
            ->where('ventas.estado', 1)
            ->where('ventas.saldoUSD', '>', 0)
            ->groupBy('clientes.idCliente', 'clientes.nombreCliente', 'clientes.celular')
            ->orderByDesc('saldoPendiente')
            ->get();
    }
}
