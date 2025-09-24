@extends('layouts.app')

@section('content')
    <h1 class="text-center text-info fw-bold"><i class="fa-solid fa-duotone fa-dashboard mx-2"></i>{{ $headTitle }}</h1>

    <h2 class="text-center"><i class="fa-solid fa-duotone fa-door-open mx-2"></i>Bienvenido, <span
            class="text-info fw-bold">{{ session('nombreUsuario') }}</span></h2>

    <div class="card">
        <div class="card-header">
            <h2 class="text-info fw-bold"><i class="fa-solid fa-duotone fa-bars"></i> MENÚ</h2>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-success" href="#">
                        <div>
                            <i class="fa-solid fa-duotone fa-cart-plus fa-2xl"></i><br />Añadir venta
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-success" href="#">
                        <div>
                            <i class="fa-solid fa-duotone fa-chart-mixed-up-circle-dollar fa-2xl"></i><br />Ventas
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-success" href="#">
                        <div>
                            <i class="fa-solid fa-duotone fa-clock-rotate-left fa-2xl"></i><br />Historial de productos
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-success" href="#">
                        <div>
                            <i class="fa-solid fa-duotone fa-chart-mixed-up-circle-dollar fa-2xl"></i><br />Reporte
                            utilidades
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-success" href="#">
                        <div>
                            <i class="fa-solid fa-duotone fa-chart-line-down fa-2xl"></i><br />Reporte pérdidas
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('marcas.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-industry fa-2xl"></i><br />Marcas
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-secondary" href="{{ route('parametros.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-sliders fa-2xl"></i><br />Parámetros
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('empleados.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-user-tag fa-2xl"></i><br />Empleados
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('usuarios.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-user-tie fa-2xl"></i><br />Usuarios
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('clientes.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-address-card fa-2xl"></i><br />Clientes
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-info" href="#">
                        <div>
                            <i class="fa-solid fa-duotone fa-building fa-2xl"></i><br />Empresas
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-info" href="#">
                        <div>
                            <i class="fa-solid fa-duotone fa-file-pen fa-2xl"></i><br />Pedidos
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-info" href="#">
                        <div>
                            <i class="fa-solid fa-duotone fa-cart-flatbed-boxes fa-2xl"></i><br />Abastecimientos
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <a class="btn btn-sq-lg btn-info" href="#">
                        <div>
                            <i class="fa-solid fa-duotone fa-boxes-stacked fa-2xl"></i><br />Productos
                        </div>
                    </a>
                </div>

                
            </div>
        </div>


    </div>
@endsection

@section('scripts')
    <script></script>
@endsection
