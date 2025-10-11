@extends('layouts.app')

@section('content')
    <h1 class="text-center text-info fw-bold"><i class="fa-solid fa-duotone fa-dashboard mx-2"></i>{{ $headTitle }}</h1>

    <h2 class="text-center"><i class="fa-solid fa-duotone fa-door-open mx-2"></i>Bienvenido, <span
            class="text-info fw-bold">{{ session('nombreUsuario') }}</span></h2>

    <div class="card mb-3">
        <div class="card-header">
            <span class="h2 text-info fw-bold align-middle"><i class="fa-solid fa-duotone fa-bars"></i> MENÚ</span>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-success" href="{{ route('ventas.crear') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-cart-plus fa-2xl"></i><br />Añadir venta
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-success" href="{{ route('ventas.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-cart-shopping fa-2xl"></i><br />Ventas
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-success" href="{{ route('ventas.utilidades') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-chart-mixed-up-circle-dollar fa-2xl"></i><br />Reporte
                            utilidades
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-success" href="{{ route('ventas.perdidas') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-chart-line-down fa-2xl"></i><br />Reporte pérdidas
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('productos.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-boxes-stacked fa-2xl"></i><br />Productos
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('abastecimientos.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-cart-flatbed-boxes fa-2xl"></i><br />Abastecimientos
                        </div>
                    </a>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('saldos-empresas.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-money-check-dollar-pen fa-2xl"></i><br />Saldos de empresas
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('pedidos-empresas.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-file-pen fa-2xl"></i><br />Pedidos a empresas
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('clientes.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-address-card fa-2xl"></i><br />Clientes
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('empresas.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-building fa-2xl"></i><br />Empresas
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('marcas.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-industry-windows fa-2xl"></i><br />Marcas
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('empleados.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-user-tag fa-2xl"></i><br />Empleados
                        </div>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-info" href="{{ route('usuarios.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-user-tie fa-2xl"></i><br />Usuarios
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center m-2">
                    <a class="btn btn-sq-lg btn-secondary" href="{{ route('parametros.index') }}">
                        <div>
                            <i class="fa-solid fa-duotone fa-sliders fa-2xl"></i><br />Parámetros
                        </div>
                    </a>
                </div>

                <div class="col d-flex justify-content-center m-2">

                </div>

                <div class="col d-flex justify-content-center m-2">

                </div>

                <div class="col d-flex justify-content-center m-2">

                </div>

                <div class="col d-flex justify-content-center m-2">

                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <span class="h2 text-info fw-bold align-middle"><i class="fa-solid fa-duotone fa-chart-simple"></i> ESTADÍSTICAS</span>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6 col-lg-4">
                    <div class="card info-card shadow-sm border-success">
                        <div class="card-body d-flex align-items-center bg-success bg-opacity-10">
                            <div class="icon-box bg-success bg-opacity-10 me-3">
                                <i class="text-success fa-solid fa-duotone fa-cart-shopping fa-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">Ventas de hoy</h6>
                                <h3 class="fw-bold">{{ $estadisticas['hoy']['cantidadVentas'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card info-card shadow-sm border-success">
                        <div class="card-body d-flex align-items-center bg-success bg-opacity-10">
                            <div class="icon-box bg-success bg-opacity-10 me-3">
                                <i class="text-success fa-solid fa-duotone fa-cart-shopping fa-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">Ventas de la semana</h6>
                                <h3 class="fw-bold">{{ $estadisticas['semana']['cantidadVentas'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card info-card shadow-sm border-success">
                        <div class="card-body d-flex align-items-center bg-success bg-opacity-10">
                            <div class="icon-box bg-success bg-opacity-10 me-3">
                                <i class="text-success fa-solid fa-duotone fa-cart-shopping fa-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">Ventas del mes</h6>
                                <h3 class="fw-bold">{{ $estadisticas['mes']['cantidadVentas'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 col-lg-4">
                    <div class="card info-card shadow-sm border-success">
                        <div class="card-body d-flex align-items-center bg-success bg-opacity-10">
                            <div class="icon-box bg-success bg-opacity-10 me-3">
                                <i class="text-success fa-solid fa-duotone fa-sack-dollar fa-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">Ingresos de hoy</h6>
                                <h3 class="fw-bold">$ {{ $estadisticas['hoy']['ingresos'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card info-card shadow-sm border-success">
                        <div class="card-body d-flex align-items-center bg-success bg-opacity-10">
                            <div class="icon-box bg-success bg-opacity-10 me-3">
                                <i class="text-success fa-solid fa-duotone fa-sack-dollar fa-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">Ingresos de la semana</h6>
                                <h3 class="fw-bold">$ {{ $estadisticas['semana']['ingresos'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card info-card shadow-sm border-success">
                        <div class="card-body d-flex align-items-center bg-success bg-opacity-10">
                            <div class="icon-box bg-success bg-opacity-10 me-3">
                                <i class="text-success fa-solid fa-duotone fa-sack-dollar fa-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">Ingresos del mes</h6>
                                <h3 class="fw-bold">$ {{ $estadisticas['mes']['ingresos'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 col-lg-4">
                    <div class="card info-card shadow-sm border-primary">
                        <div class="card-body d-flex align-items-center bg-primary bg-opacity-10">
                            <div class="icon-box bg-primary bg-opacity-10 me-3">
                                <i class="text-primary fa-solid fa-duotone fa-boxes-stacked fa-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">Productos vendidos de hoy</h6>
                                <h3 class="fw-bold">{{ $estadisticas['hoy']['productosVendidos'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card info-card shadow-sm border-primary">
                        <div class="card-body d-flex align-items-center bg-primary bg-opacity-10">
                            <div class="icon-box bg-primary bg-opacity-10 me-3">
                                <i class="text-primary fa-solid fa-duotone fa-boxes-stacked fa-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">Productos vendidos de la semana</h6>
                                <h3 class="fw-bold">{{ $estadisticas['semana']['productosVendidos'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card info-card shadow-sm border-primary">
                        <div class="card-body d-flex align-items-center bg-primary bg-opacity-10">
                            <div class="icon-box bg-primary bg-opacity-10 me-3">
                                <i class="text-primary fa-solid fa-duotone fa-boxes-stacked fa-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">Productos vendidos del mes</h6>
                                <h3 class="fw-bold">{{ $estadisticas['mes']['productosVendidos'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">

                    <h4 class="fw-bold"><i class="fa-solid fa-duotone fa-cart-shopping"></i> SALDOS PENDIENTES</h4>

                    <div class="border p-3 mb-3 rounded">
                        <p>Seleccione una opción para <i class="fa-solid fa-duotone fa-file-export"></i> exportar o <i
                                class="fa-solid fa-duotone fa-filter"></i> filtrar la tabla:</p>
                        <div id="dataTableExportButtonsContainer"></div>
                    </div>

                    <table class="table table-bordered table-striped" id="dataTable">
                        <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Celular</th>
                                <th>Procedencia</th>
                                <th>Saldo (USD)</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($saldos_pendientes as $saldo_pendiente)
                                <tr>
                                    <th class="text-center">{{ $loop->index + 1 }}.</th>
                                    <th>{{ $saldo_pendiente->nombreCliente }}</th>
                                    <th>{{ $saldo_pendiente->celular }}</th>
                                    <th>{{ $saldo_pendiente->procedencia }}</th>
                                    <th>{{ $saldo_pendiente->saldoPendiente }}</th>
                                    <th>{{ date('d/m/Y H:i:s', strtotime($saldo_pendiente->fechaMasAntigua)) }}</th>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .info-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .icon-box {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 28px;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                @include('datatables.dataTablesGlobalProperties')
                @include('datatables.dataTablesLanguageProperty')
            }).buttons().container().appendTo('#dataTableExportButtonsContainer');
        });
    </script>
@endsection
