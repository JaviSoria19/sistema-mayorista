<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmpleadoController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ruta por defecto
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*Estructura de Laravel => Route::get(URL web, método de controlador)->name(nombre para referenciar ruta)*/

Route::controller(UsuarioController::class)->group(function(){
    /* Rutas para gestionar la sesión del usuario y el panel de administración */
    Route::get('panel','view_dashboard')->name('dashboard');
    Route::get('iniciar-sesion','view_iniciar_sesion')->name('login');
    Route::get('cerrar-sesion','cerrar_sesion')->name('logout');
    Route::post('verificar','verificar')->name('login.verificar');

    /* Rutas para gestionar los registros de la tabla 'Usuarios' */
    Route::get('usuarios','view_index')->name('usuarios.index');
    Route::get('usuarios/listar','listarUsuarios')->name('usuarios.listar');
    Route::get('usuarios/{usuario}','mostrarUsuario')->name('usuarios.mostrar');
    Route::post('usuarios','create')->name('usuarios.create');
    Route::put('usuarios/{usuario}','update')->name('usuarios.update');
    Route::patch('usuarios/{usuario}','deleteOrRestore')->name('usuarios.deleteOrRestore');
});
