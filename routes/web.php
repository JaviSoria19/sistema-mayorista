<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ParametroController;

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

Route::controller(UsuarioController::class)->group(function () {
    /* Rutas para gestionar la sesión del usuario y el panel de administración */
    Route::get('panel', 'view_dashboard')->name('dashboard');
    Route::get('iniciar-sesion', 'view_iniciar_sesion')->name('login');
    Route::get('cerrar-sesion', 'cerrar_sesion')->name('logout');
    Route::post('verificar', 'verificar')->name('login.verificar');

    /* Rutas para gestionar los registros de la tabla 'Usuarios' */
    Route::get('usuarios', 'view_index')->name('usuarios.index');
    Route::get('usuarios/listar', 'listarUsuarios')->name('usuarios.listar');
    Route::get('usuarios/{usuario}', 'mostrarUsuario')->name('usuarios.mostrar');
    Route::post('usuarios', 'create')->name('usuarios.create');
    Route::put('usuarios/{usuario}', 'update')->name('usuarios.update');
    Route::patch('usuarios/{usuario}', 'deleteOrRestore')->name('usuarios.deleteOrRestore');
});

Route::controller(EmpleadoController::class)->group(function () {
    Route::get('empleados', 'view_index')->name('empleados.index');
    Route::get('empleados/listar', 'listarEmpleados')->name('empleados.listar');
    Route::get('empleados/{empleado}', 'mostrarEmpleado')->name('empleados.mostrar');
    Route::post('empleados', 'create')->name('empleados.create');
    Route::put('empleados/{empleado}', 'update')->name('empleados.update');
});

Route::controller(ParametroController::class)->group(function () {
    Route::get('parametros', 'view_index')->name('parametros.index');
    Route::put('parametros/{parametro}', 'update')->name('parametros.update');
});

Route::controller(MarcaController::class)->group(function () {
    Route::get('marcas', 'view_index')->name('marcas.index');
    Route::get('marcas/listar', 'listarMarcas')->name('marcas.listar');
    Route::get('marcas/{marca}', 'mostrarMarca')->name('marcas.mostrar');
    Route::post('marcas', 'create')->name('marcas.create');
    Route::put('marcas/{marca}', 'update')->name('marcas.update');
    Route::patch('marcas/{marca}', 'deleteOrRestore')->name('marcas.deleteOrRestore');
});

Route::controller(ClienteController::class)->group(function () {
    Route::get('clientes', 'view_index')->name('clientes.index');
    Route::get('clientes/listar', 'listarClientes')->name('clientes.listar');
    Route::get('clientes/{cliente}', 'mostrarCliente')->name('clientes.mostrar');
    Route::post('clientes', 'create')->name('clientes.create');
    Route::put('clientes/{cliente}', 'update')->name('clientes.update');
    Route::patch('clientes/{cliente}', 'deleteOrRestore')->name('clientes.deleteOrRestore');
});

Route::controller(EmpresaController::class)->group(function () {
    Route::get('empresas', 'view_index')->name('empresas.index');
    Route::get('empresas/listar', 'listarEmpresas')->name('empresas.listar');
    Route::get('empresas/{empresa}', 'mostrarEmpresa')->name('empresas.mostrar');
    Route::post('empresas', 'create')->name('empresas.create');
    Route::put('empresas/{empresa}', 'update')->name('empresas.update');
    Route::patch('empresas/{empresa}', 'deleteOrRestore')->name('empresas.deleteOrRestore');
});
