<<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ helper_tituloPagina() }} | VENTA N° {{ $venta->idVenta }}
            {{ $venta->estado == '0' ? '(ELIMINADA)' : '' }}</title>
        <!-- Bootstrap CSS -->
        <link href="{{ asset('/public/dependencies/bootstrapdompdf.css') }}" rel="stylesheet">
    </head>

    <body class="border border-{{ $venta->estado == '0' ? 'danger' : 'info' }}">
        <style>
            html {
                margin: 25px;
            }

            .titulo {
                font-size: 30px;
                font-weight: bold;
            }

            .subtitulo {
                font-size: 20px;
                font-weight: bold;
            }

            .watermark {
                position: fixed;
                top: 0%;
                left: 28%;
                width: 300px;
                opacity: 0.15;
                z-index: -1000;
            }

            .table-container {
                display: flex;
                flex-direction: column;
            }

            .table-container table {
                margin: 0;
            }

            .table-container table td {
                padding: 0;
                padding-left: 2px;
                padding-right: 2px;
            }

            .table-container table th {
                padding: 0;
                padding-left: 2px;
                padding-right: 2px;
            }

            .inicio {
                margin: 0;
                padding: 0;
            }
        </style>
        <img src="{{ public_path('img/logo_sistema_mayorista.jpg') }}" class="watermark">

        <p
            class="font-weight-bold bg-{{ $venta->estado == '0' ? 'danger' : 'info' }} text-white m-2 text-center rounded align-middle">
            VENTA N° {{ $venta?->idVenta }}
            {{ $venta->estado == '0' ? '(ELIMINADA EL ' . date('d/m/Y H:i:s', strtotime($venta?->fechaEliminacion)) . ')' : '' }}
        </p>

        <div class="table-container border border-info rounded p-2 m-2">
            <table class="table table-bordered">
                <tr>
                    <td class="font-weight-bold">
                        <span class="text-info">Empleado:</span> {{ $venta?->empleado->nombreEmpleado }}<br>
                        <span class="text-info">Usuario:</span> {{ $venta?->usuario->nombreUsuario }}
                    </td>
                    <td class="font-weight-bold text-right">
                        <span class="text-info">F. Registro:</span>
                        {{ date('d/m/Y H:i:s', strtotime($venta?->fechaRegistro)) }}<br>
                        <span class="text-info">F. Actualización:</span>
                        {{ date('d/m/Y H:i:s', strtotime($venta?->fechaActualizacion)) }}
                    </td>
                </tr>
                @if ($venta->fechaEliminacion)
                    <tr>
                        <td class="font-weight-bold text-center" colspan="2">
                            <span class="text-info">Fecha de Eliminación:</span>
                            {{ date('d/m/Y H:i:s', strtotime($venta?->fechaEliminacion)) }}
                        </td>
                    </tr>
                @endif
            </table>

            <div class="text-center font-weight-bold"><span class="text-info">Cliente:</span>
                {{ $venta?->cliente->nombreCliente }} - <span class="text-info">Celular:</span>
                {{ $venta?->cliente->celular }} - <span class="text-info">Procedencia:</span>
                {{ $venta?->cliente->procedencia }}</div>

            @php
                $productosAgrupados = collect($venta?->productos)->groupBy(function ($producto) {
                    // Clave de agrupación (marca + nombre + precio)
                    return $producto->marca->nombreMarca .
                        '|' .
                        $producto->nombreProducto .
                        '|' .
                        $producto->pivot->precioUSD;
                });

                $total = 0;
            @endphp

            <table class="table table-bordered table-striped" style="border-color:1px black">
                <thead class="bg-secondary text-light text-center">
                    <tr>
                        <th>#</th>
                        <th>PRODUCTO</th>
                        <th>CANTIDAD</th>
                        <th>PRECIO UNIT. (USD)</th>
                        <th>SUBTOTAL (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productosAgrupados as $index => $grupo)
                        @php
                            $cantidad = $grupo->count();
                            $precioUnitario = $grupo->first()->pivot->precioUSD;
                            $subtotal = $cantidad * $precioUnitario;
                            $total += $subtotal;
                            $producto = $grupo->first();
                        @endphp
                        <tr>
                            <td class="text-center"><b>{{ $loop->iteration }}.</b></td>
                            <td>
                                {{-- <span class="text-info">{{ $producto->codigoProducto }}</span> --}}
                                {{ $producto->marca->nombreMarca }} {{ $producto->nombreProducto }}
                            </td>
                            <td class="text-center">{{ $cantidad }}</td>
                            <td class="text-right">{{ number_format($precioUnitario, 2) }}</td>
                            <td class="text-right">{{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right">TOTAL (USD):</td>
                        <td class="text-right font-weight-bold">{{ number_format($total, 2) }}</td>
                    </tr>
                </tbody>
            </table>


            <p class="subtitulo text-info text-center m-0">--- PAGOS ---</p>

            <table class="table table-bordered table-striped">
                <thead class="bg-secondary text-light text-center">
                    <tr>
                        <th>#</th>
                        <th>FECHA</th>
                        <th>PAGO (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venta?->pagos as $index => $pago)
                        <tr>
                            <td class="text-center"><b>{{ $index + 1 }}.</b></td>
                            <td>{{ date('d/m/Y H:i:s', strtotime($pago->fechaRegistro)) }}</td>
                            <td class="text-right"><span
                                    class="text-success font-weight-bold">{{ $pago->pagoUSD }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">SALDO (USD):</th>
                        <th class="text-right">{{ number_format($venta->saldoUSD, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- 

        <div class="m-2">
            

             --}}

        <p class="text-center">Muchas gracias por tu compra!</p>
        </div>



    </body>

    </html>
