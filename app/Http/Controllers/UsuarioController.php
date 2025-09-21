<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function view_iniciar_sesion()
    {
        return view('login');
    }
    
    public function view_dashboard()
    {
        /*Si no tiene acceso, se redirige a la ventana de inicio de sesión.*/
        if (!session('tieneAcceso')) {
            return redirect()->route('login');
        }
        /*Al ingresar a la vista del panel de administración, se verifica si el usuario aún tiene acceso al sistema.*/
        $usuario = (new Usuario())->getUsuario(session('idUsuario'));
        if ($usuario->estado == '0') {
            session(['tieneAcceso' => false]);
        }

        return view('Panel.admin', [
            'headTitle' => 'PANEL DE ADMINISTRACIÓN',
        ]);
    }

    public function view_index()
    {
        if (!session('tieneAcceso')) {
            return redirect()->route('login');
        }

        $usuarios = (new Usuario())->getAllUsuarios();

        return view('Usuario.inicio', [
            'headTitle' => 'USUARIOS',
            'usuarios' => $usuarios,
        ]);
    }

    public function view_edit(Request $request)
    {
        if (!session('tieneAcceso')) {
            return redirect()->route('login');
        }

        $empleados = (new \App\Models\Empleado())->all();

        $usuario = null;
        if ($request->has('idUsuario')) {
            $usuario = (new Usuario())->getUsuario($request->idUsuario);
            if (!$usuario) {
                return redirect()->route('usuarios.index');
            }
        }

        return;
    }

    public function verificar(Request $request)
    {
        $Usuario = (new Usuario())->login(
            trim(strtoupper($request->nombreUsuario))
        );

        if (!$Usuario) {
            return redirect()->route('login')->with([
                'mensaje' => 'EL USUARIO NO EXISTE.',
                'loginNombreUsuario' => $request->nombreUsuario,
                'loginContrasenha' => $request->contrasenha,
            ]);
        }
        if ($Usuario->estado == '0') {
            return redirect()->route('login')->with([
                'mensaje' => 'EL USUARIO NO TIENE ACCESO AL SISTEMA.',
                'loginNombreUsuario' => $request->nombreUsuario,
                'loginContrasenha' => $request->contrasenha,
            ]);
        }
        if ($request->contrasenha != helper_decrypt($Usuario->contrasenha)) {
            return redirect()->route('login')->with([
                'mensaje' => 'LA CONTRASEÑA ES INCORRECTA.',
                'loginNombreUsuario' => $request->nombreUsuario,
                'loginContrasenha' => $request->contrasenha,
            ]);
        }
        //Si el usuario y la contraseña son correctos, se crea la sesión y se redirige al panel de administración.
        session([
            'tieneAcceso' => true,
            'idUsuario' => $Usuario->idUsuario,
            'idEmpleado' => $Usuario->idEmpleado,
            'nombreUsuario' => $Usuario->nombreUsuario,
        ]);
        return redirect()->route('dashboard');
    }

    public function cerrar_sesion()
    {
        (new Usuario())->logout();
        return redirect()->route('login');
    }
    
    public function create(Request $request)
    {
        if (!session('tieneAcceso')) {
            return redirect()->route('login');
        }
        $request->validate([
            'idEmpleado' => ['required', 'numeric', 'integer', 'unique:usuarios'],
            'nombreUsuario' => ['required', 'string', 'max:50', 'unique:usuarios'],
            'contrasenha' => ['required', 'string', 'min:8', 'max:100'],
        ]);
        $usuario = new Usuario();
        $usuario->idEmpleado = $request->idEmpleado;
        $usuario->nombreUsuario = $request->nombreUsuario;
        $usuario->contrasenha = helper_encrypt($request->contrasenha);
        $usuario->save();
        return $usuario;
    }
    
    public function update(Request $request, $idUsuario)
    {
        if (!session('tieneAcceso')) {
            return redirect()->route('login');
        }
        $request->validate([
            'idEmpleado' => ['required', 'numeric', 'integer', 'unique:usuarios,idEmpleado,' . $idUsuario . ',idUsuario'],
            'nombreUsuario' => ['required', 'string', 'max:50', 'unique:usuarios,nombreUsuario,' . $idUsuario . ',idUsuario'],
            'contrasenha' => ['nullable', 'string', 'min:8', 'max:100'],
        ]);
        $usuario = (new Usuario())->getUsuario($idUsuario);
        $usuario->idEmpleado = $request->idEmpleado;
        $usuario->nombreUsuario = $request->nombreUsuario;
        if ($request->contrasenha) {
            $usuario->contrasenha = helper_encrypt($request->contrasenha);
        }
        $usuario->save();
        return $usuario;
    }

    /**Método que permite ELIMINAR (soft delete) o RESTAURAR un registro de la tabla 'Usuarios' y retorna el objeto de la clase Usuario con el atributo 'estado' actualizado.*/
    public function deleteOrRestore(Request $request)
    {
        if (!session('tieneAcceso')) {
            return redirect()->route('login');
        }

        $request->validate([
            'idUsuario' => ['required', 'numeric', 'integer']
        ]);
        $usuario = (new Usuario())->getUsuario($request->idUsuario);
        $usuario->estado = $usuario->estado == '1' ? '0' : '1';
        $usuario->save();
        return redirect()->route('usuarios.index');
    }

    public function listarUsuarios()
    {
        if (!session('tieneAcceso')) {
            return redirect()->route('login');
        }

        $usuarios = (new Usuario())->getAllUsuarios();
        return response()->json([
            'data' => $usuarios
        ]);
    }
}
