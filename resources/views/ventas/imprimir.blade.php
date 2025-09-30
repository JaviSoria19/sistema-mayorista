<<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ helper_tituloPagina() }} | VENTA N° {{ $venta->idVenta }}</title>
        <!-- Bootstrap CSS -->
        <link href="{{ asset('/public/dependencies/bootstrapdompdf.css') }}" rel="stylesheet">
    </head>

    <body class="border border-info">
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
                top: 34.5%;
                left: 28%;
                width: 300px;
                opacity: 0.15;
                z-index: -1000;
            }
        </style>
        <img src="{{ public_path('img/logo_sistema_mayorista.jpg') }}" class="watermark">

        <p class="titulo bg-info text-white p-1 m-2 text-center rounded align-middle">VENTA N° {{ $venta?->idVenta }}
        </p>

        <div class="border border-info rounded p-2 m-2">
            <table class="table table-bordered">
                <tr>
                    <td class="font-weight-bold">
                        <span class="text-info">Empleado:</span> {{ $venta?->empleado->nombreEmpleado }}<br>
                        <span class="text-info">Usuario:</span> {{ $venta?->usuario->nombreUsuario }}
                    </td>
                    <td class="font-weight-bold">
                        <span class="text-info">Fecha de Registro:</span>
                        {{ date('d/m/Y H:i:s', strtotime($venta?->fechaRegistro)) }}<br>
                        <span class="text-info">Fecha de Actualización:</span>
                        {{ date('d/m/Y H:i:s', strtotime($venta?->fechaActualizacion)) }}
                    </td>
                </tr>
            </table>

            <table class="table table-bordered">
                <tr>
                    <td class="font-weight-bold">
                        <span class="text-info">Cliente:</span> {{ $venta?->cliente->nombreCliente }} - <span
                            class="text-info">Celular:</span> {{ $venta?->cliente->celular }}<br>
                        <span class="text-info">Procedencia:</span> {{ $venta?->cliente->procedencia }}
                    </td>
                </tr>
            </table>
        </div>

        <p class="subtitulo text-info text-center">DETALLES</p>

        <div class="m-2">
            <table class="table table-bordered table-striped">
                <thead class="bg-secondary text-light text-center">
                    <tr>
                        <th>#</th>
                        <th>PRODUCTO</th>
                        <th>PRECIO (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($venta?->productos as $index => $producto)
                        @php
                            $total += $producto->pivot->precioUSD;
                        @endphp

                        <tr>
                            <td class="text-center"><b>{{ $index + 1 }}.</b></td>
                            <td><span class="text-info">{{ $producto->codigoProducto }}</span>
                                {{ $producto->marca->nombreMarca }} {{ $producto->nombreProducto }}</td>
                            <td class="text-right">{{ number_format($producto->pivot->precioUSD, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">TOTAL (USD):</th>
                        <th class="text-right">{{ number_format($total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>

            <table class="table table-bordered table-striped">
                <thead class="bg-secondary text-light text-center">
                    <tr>
                        <th>#</th>
                        <th>FECHA</th>
                        <th>PAGOS (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venta?->pagos as $index => $pago)
                        <tr>
                            <td class="text-center"><b>{{ $index + 1 }}.</b></td>
                            <td>{{ date('d/m/Y H:i:s', strtotime($pago->fechaRegistro)) }}</td>
                            <td class="text-right"><span class="text-success font-weight-bold">{{ $pago->pagoUSD }}</span></td>
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

            <p class="text-center">Muchas gracias por tu compra!</p>
        </div>



    </body>

    </html>
