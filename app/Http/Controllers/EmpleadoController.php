<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    public function index()
    {
        return view('Empleado.index', ['headTitle' => 'Gestión de Empleados']);
    }
    
    public function getData(Request $request)
    {
        $empleados = Empleado::all()->with('modificadoPorNombre');
        return response()->json(['data' => $empleados]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombreEmpleado' => 'required|string|max:255'
        ]);

        try {
            $empleado = Empleado::create([
                'nombreEmpleado' => $request->nombreEmpleado,
                'estado' => $request->estado ?? 1,
                'modificadoPor' => Auth::id() ?? 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Empleado creado exitosamente',
                'data' => $empleado
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el empleado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Empleado $empleado)
    {
        return response()->json([
            'success' => true,
            'data' => $empleado
        ]);
    }

    public function update(Request $request, Empleado $empleado)
    {
        $request->validate([
            'nombreEmpleado' => 'required|string|max:255'
        ]);

        try {
            $empleado->update([
                'nombreEmpleado' => $request->nombreEmpleado,
                'estado' => $request->estado ?? 1,
                'modificadoPor' => Auth::id() ?? 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Empleado actualizado exitosamente',
                'data' => $empleado
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el empleado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Empleado $empleado)
    {
        try {
            
            $empleado->update([
                'estado' => $empleado->estado == 1 ? 0 : 1,
                'modificadoPor' => Auth::id() ?? 0
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Empleado desactivado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desactivar el empleado: ' . $e->getMessage()
            ], 500);
        }
    }
}