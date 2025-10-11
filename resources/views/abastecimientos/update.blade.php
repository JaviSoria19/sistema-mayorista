@extends('layouts.app')

@section('content')
    <h1 class="text-center text-info fw-bold"><i class="fa-solid fa-duotone fa-cart-flatbed-boxes"></i> {{ $headTitle }} N°
        {{ $abastecimiento->idAbastecimiento }}</h1>

    <h2 class="text-info fw-bold">Lista de productos</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr class="text-center align-middle">
                <th>#</th>
                <th>Empresa</th>
                <th>Marca</th>
                <th>Producto</th>
                <th>Código</th>
                <th>Costo base (USD)</th>
                <th>Costo traspaso (%)</th>
                <th>Costo transporte (USD)</th>
                <th>Estado</th>
                <th>Imprimir código</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($abastecimiento->productos as $producto)
                @php
                    $backgroundColor = match ($producto->estado) {
                        0 => 'table-secondary border-secondary', // Eliminado
                        1 => 'table-success border-success', // Disponible
                        2 => 'table-warning border-warning', // Vendido
                        default => '', // Sin estado definido
                    };

                    $estado = match ($producto->estado) {
                        0 => 'Eliminado',
                        1 => 'Disponible',
                        2 => 'Vendido',
                        default => 'Desconocido',
                    };

                    $badgeColor = match ($producto->estado) {
                        0 => 'bg-secondary', // Eliminado
                        1 => 'bg-success', // Disponible
                        2 => 'bg-warning', // Vendido
                        default => 'bg-dark', // Sin estado definido
                    };
                @endphp
                <tr class="{{ $backgroundColor }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <select style="width: 100%" class="form-select empresa" name="idEmpresa">
                            <option value="{{ $producto->idEmpresa }}" selected>{{ $producto->empresa->nombreEmpresa }}
                            </option>
                            @foreach ($empresas as $empresa)
                                <option value="{{ $empresa->idEmpresa }}">{{ $empresa->nombreEmpresa }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select style="width: 100%" class="form-select marca" name="idMarca">
                            <option value="{{ $producto->idMarca }}" selected>{{ $producto->marca->nombreMarca }}</option>
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->idMarca }}">{{ $marca->nombreMarca }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="nombreProducto" {{ $producto->estado == 1 ? 'contenteditable=true' : '' }}>{{ $producto->nombreProducto }}</td>
                    <td class="text-primary fw-bold">{{ $producto->codigoProducto }}</td>
                    <td class="text-success fw-bold costoBaseUSD"
                        {{ $producto->estado == 1 ? 'contenteditable=true' : '' }}>
                        {{ number_format($producto->costoBaseUSD, 2) }}</td>
                    <td class="traspasoPorcentaje" {{ $producto->estado == 1 ? 'contenteditable=true' : '' }}>
                        {{ $producto->traspasoPorcentaje }}</td>
                    <td class="transporteUSD" {{ $producto->estado == 1 ? 'contenteditable=true' : '' }}>
                        {{ number_format($producto->transporteUSD, 2) }}</td>
                    <td><span class="badge {{ $badgeColor }}">{{ $estado }}</span></td>
                    <td>
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
    @include('abastecimientos.update_scripts')
@endsection
