<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Abastecimiento;
use App\Models\Empresa;
use App\Models\Marca;
use App\Models\Parametro;
use App\Models\Producto;
use Illuminate\Http\Request;

class AbastecimientoController extends Controller
{
    public function view_index()
    {
        if (!session('tieneAcceso')) {
            return redirect()->route('login');
        }

        $parametro = (new Parametro())->getParametro();
        $empresas = (new Empresa())->getAllEmpresas();
        $marcas = (new Marca())->getAllMarcas();
        $productos = (new Producto())->getAllProductosGroupByNombreProducto();

        return view('abastecimientos.index', [
            'headTitle' => 'GESTIÓN DE ABASTECIMIENTOS',
            'parametro' => $parametro,
            'empresas' => $empresas,
            'marcas' => $marcas,
            'productos' => $productos,
        ]);
    }

    public function view_update($abastecimiento){
        if (!session('tieneAcceso')) {
            return redirect()->route('login');
        }

        $abastecimiento = (new Abastecimiento())->getAbastecimiento($abastecimiento);
        $parametro = (new Parametro())->getParametro();
        $empresas = (new Empresa())->getAllEmpresas();
        $marcas = (new Marca())->getAllMarcas();
        $productos = (new Producto())->getAllProductosGroupByNombreProducto();

        return view('abastecimientos.update', [
            'headTitle' => 'ACTUALIZAR ABASTECIMIENTO',
            'abastecimiento' => $abastecimiento,
            'parametro' => $parametro,
            'empresas' => $empresas,
            'marcas' => $marcas,
            'productos' => $productos,
        ]);
    }

    public function listarAbastecimientos()
    {
        if (!session('tieneAcceso')) {
            return response()->json(['success' => false, 'message' => 'No tiene acceso'], 403);
        }

        $abastecimientos = (new Abastecimiento())->getAllAbastecimientos();
        return response()->json([
            'data' => $abastecimientos
        ]);
    }

    public function mostrarAbastecimiento(Request $request)
    {
        if (!session('tieneAcceso')) {
            return response()->json(['success' => false, 'message' => 'No tiene acceso'], 403);
        }

        $abastecimiento = (new Abastecimiento())->getAbastecimiento($request->abastecimiento);
        return response()->json([
            'data' => $abastecimiento
        ]);
    }

    public function create(Request $request)
    {
        if (!session('tieneAcceso')) {
            return response()->json(['success' => false, 'message' => 'No tiene acceso'], 403);
        }

        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.nombreProducto' => 'required|string|min:3|max:255',
            'productos.*.codigoProducto' => 'required|string|min:3|max:100',
            'productos.*.costoBaseUSD' => 'required|numeric|min:0|max:99999.99',
            'productos.*.traspasoPorcentaje' => 'required|numeric|min:0|max:999.99',
            'productos.*.transporteUSD' => 'required|numeric|min:0|max:99999.99',
        ]);

        DB::beginTransaction();
        try {
            $abastecimiento = new Abastecimiento();
            $abastecimiento->modificadoPor = session('idUsuario');
            $abastecimiento->save();

            foreach ($request->productos as $producto) {
                $p = new Producto();
                $p->idEmpresa = $producto['idEmpresa'];
                $p->idMarca = $producto['idMarca'];
                $p->idAbastecimiento = $abastecimiento->idAbastecimiento;
                $p->nombreProducto = $producto['nombreProducto'];
                $p->codigoProducto = $abastecimiento->idAbastecimiento . '-' . $producto['codigoProducto'];
                $p->costoBaseUSD = $producto['costoBaseUSD'];
                $p->traspasoPorcentaje = $producto['traspasoPorcentaje'];
                $p->transporteUSD = $producto['transporteUSD'];
                $p->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Abastecimiento de productos registrado correctamente',
                'abastecimiento' => $abastecimiento->load('productos')
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request){
        if (!session('tieneAcceso')) {
            return response()->json(['success' => false, 'message' => 'No tiene acceso'], 403);
        }

        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.nombreProducto' => 'required|string|min:3|max:255',
            'productos.*.codigoProducto' => 'required|string|min:3|max:100',
            'productos.*.costoBaseUSD' => 'required|numeric|min:0|max:99999.99',
            'productos.*.traspasoPorcentaje' => 'required|numeric|min:0|max:999.99',
            'productos.*.transporteUSD' => 'required|numeric|min:0|max:99999.99',
        ]);

        return;
    }
}
