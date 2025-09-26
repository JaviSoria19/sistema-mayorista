<?php

namespace App\Http\Controllers;

use App\Models\SaldoEmpresa;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\PedidoEmpresa;

class PedidoEmpresaController extends Controller
{
    public function view_index()
    {
        if (!session('tieneAcceso')) {
            return redirect()->route('login');
        }

        $empresas = (new Empresa())->getAllEmpresas();

        return view('pedidos_empresas.index', [
            'headTitle' => 'GESTIÓN DE PEDIDOS A EMPRESAS',
            'empresas' => $empresas,
        ]);
    }

    public function listarPedidosEmpresas()
    {
        if (!session('tieneAcceso')) {
            return response()->json(['success' => false, 'message' => 'No tiene acceso'], 403);
        }

        $pedidos_empresas = (new PedidoEmpresa())->getAllPedidosEmpresas();
        return response()->json([
            'data' => $pedidos_empresas
        ]);
    }

    public function mostrarPedidoEmpresa(Request $request)
    {
        if (!session('tieneAcceso')) {
            return response()->json(['success' => false, 'message' => 'No tiene acceso'], 403);
        }

        $pedido_empresa = (new PedidoEmpresa())->getPedidoEmpresa($request->pedido_empresa);
        return response()->json([
            'data' => $pedido_empresa
        ]);
    }

    public function create(Request $request)
    {
        if (!session('tieneAcceso')) {
            return response()->json(['success' => false, 'message' => 'No tiene acceso'], 403);
        }

        $pedido_empresa = new PedidoEmpresa();
        $pedido_empresa->idEmpresa = $request->idEmpresa;
        $pedido_empresa->montoUSD = $request->montoUSD;
        $pedido_empresa->pagoUSD = $request->pagoUSD;
        $pedido_empresa->save();

        return response()->json([
            'success' => true,
            'message' => 'Saldo de empresa registrado correctamente',
            'pedido_empresa' => $pedido_empresa
        ]);
    }

    public function update(Request $request, $idPedidoEmpresa)
    {
        if (!session('tieneAcceso')) {
            return response()->json(['success' => false, 'message' => 'No tiene acceso'], 403);
        }

        $pedido_empresa = (new PedidoEmpresa())->getPedidoEmpresa($idPedidoEmpresa);
        $pedido_empresa->idEmpresa = $request->idEmpresa;
        $pedido_empresa->montoUSD = $request->montoUSD;
        $pedido_empresa->pagoUSD = $request->pagoUSD;
        $pedido_empresa->modificadoPor = session('idUsuario');
        $pedido_empresa->save();

        return response()->json([
            'success' => true,
            'message' => 'Saldo de empresa actualizado correctamente',
            'pedido_empresa' => $pedido_empresa
        ]);
    }

    public function deleteOrRestore(Request $request)
    {
        if (!session('tieneAcceso')) {
            return response()->json(['success' => false, 'message' => 'No tiene acceso'], 403);
        }

        $request->validate([
            'idSaldoEmpresa' => ['required', 'numeric', 'integer']
        ]);

        $pedido_empresa = (new SaldoEmpresa())->getSaldoEmpresa($request->idSaldoEmpresa);
        $pedido_empresa->estado = $pedido_empresa->estado == '1' ? '0' : '1';
        $pedido_empresa->modificadoPor = session('idUsuario');
        $pedido_empresa->save();
        return response()->json([
            'success' => true,
            'message' => $pedido_empresa->estado == '1' ? 'El saldo de empresa fue restaurado con éxito' : 'El saldo de empresa fue archivado con éxito' ,
            'pedido_empresa' => $pedido_empresa
        ]);
    }
}
