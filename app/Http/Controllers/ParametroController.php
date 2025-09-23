<?php

namespace App\Http\Controllers;

use App\Models\Parametro;
use Illuminate\Http\Request;

class ParametroController extends Controller
{
    public function view_index()
    {
        if (!session('tieneAcceso')) {
            return redirect()->route('login');
        }

        $parametro = (new Parametro())->getParametro();

        return view('parametros.index', [
            'headTitle' => 'PARÁMETROS',
            'parametro' => $parametro,
        ]);
    }
    public function update(Request $request, $idParametro)
    {
        if (!session('tieneAcceso')) {
            return response()->json(['success' => false, 'message' => 'No tiene acceso'], 403);
        }
        $request->validate([
            'paramPorcentajeTraspaso' => ['required', 'numeric', 'min:0.00', 'max:999.99','regex:/^\d+(\.\d{1,2})?$/'],
            'paramTransporteUSD' => ['required', 'numeric', 'min:0.00', 'max:999.99','regex:/^\d+(\.\d{1,2})?$/'],
        ]);

        $parametro = (new Parametro())->getParametro();
        $parametro->paramPorcentajeTraspaso = $request->paramPorcentajeTraspaso;
        $parametro->paramTransporteUSD = $request->paramTransporteUSD;
        $parametro->modificadoPor = session('idUsuario');
        $parametro->save();

        return response()->json([
            'success' => true,
            'message' => 'Parametro actualizado correctamente',
            'parametro' => $parametro
        ]);
    }
}
