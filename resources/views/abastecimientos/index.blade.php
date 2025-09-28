@extends('layouts.app')

@section('content')
    <h1 class="text-center text-info fw-bold"><i class="fa-solid fa-duotone fa-cart-flatbed-boxes"></i> {{ $headTitle }}
    </h1>

    <button type="button" class="btn btn-success mb-3 btn-crear" data-bs-toggle="modal" data-bs-target="#modalCreate">
        <i class="fa-solid fa-duotone fa-plus"></i> Crear abastecimiento</button>

    <h2 class="text-info fw-bold">Lista de abastecimientos</h2>

    <div class="card p-3 mb-3">
        <p>Seleccione una opción para <i class="fa-solid fa-duotone fa-file-export"></i> exportar o <i
                class="fa-solid fa-duotone fa-filter"></i> filtrar la tabla:</p>
        <div id="dataTableExportButtonsContainer"></div>
    </div>

    <table class="table table-bordered table-striped" id="dataTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Productos</th>
                <th>Detalles</th>
                <th>F. Registro</th>
                <th>F. Actualización</th>
                <th>Modificado Por</th>
            </tr>
        </thead>
    </table>

    <div class="mb-3"></div>

    <!-- Modal para crear y editar abastecimientos -->
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreate_Title" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalCreate_Title"><i class="fa-solid fa-duotone fa-plus"></i>
                        CREAR ABASTECIMIENTO</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCreate">
                        <div class="mb-3">
                            <label for="empresa" class="form-label">Empresa <span class="text-danger">*</span></label><br>
                            <select style="width: 100%" class="form-select" id="empresa" name="idEmpresa" required>
                                <option value="" disabled selected>Seleccione un empresa</option>
                                @foreach ($empresas as $empresa)
                                    <option value="{{ $empresa->idEmpresa }}">{{ $empresa->nombreEmpresa }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca <span class="text-danger">*</span></label><br>
                            <select style="width: 100%" class="form-select" id="marca" name="idMarca" required>
                                <option value="" disabled selected>Seleccione una marca</option>
                                @foreach ($marcas as $marca)
                                    <option value="{{ $marca->idMarca }}">{{ $marca->nombreMarca }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nombreProducto" class="form-label">Producto <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombreProducto" name="nombreProducto"
                                list="productos" required>
                        </div>

                        <datalist id="productos">
                            @foreach ($productos as $producto)
                                <option>{{ $producto->nombreProducto }}</option>
                            @endforeach
                        </datalist>

                        <div class="mb-3">
                            <label for="costoBaseUSD" class="form-label">Costo base (USD) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="costoBaseUSD" name="costoBaseUSD" required>
                        </div>

                        <div class="mb-3">
                            <label for="traspasoPorcentaje" class="form-label">Costo Traspaso (%) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="traspasoPorcentaje" name="traspasoPorcentaje"
                                value="{{ $parametro->paramPorcentajeTraspaso }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="transporteUSD" class="form-label">Costo Transporte USD: <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="transporteUSD" name="transporteUSD"
                                value="{{ $parametro->paramTransporteUSD }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad (Unidades) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" step="1"
                                pattern="\d*" placeholder="Solo números enteros" required>
                        </div>

                        <div class="mb-3">
                            <button type="button" id="btnAdd" class="btn btn-success"><i
                                    class="fa-solid fa-duotone fa-plus"></i>
                                Añadir a la tabla</button>

                            <button type="button" id="btnEmptyTable" class="btn btn-danger"><i
                                    class="fa-solid fa-duotone fa-trash-can-list"></i>
                                Vaciar tabla</button>
                        </div>

                        <div class="mb-3">
                            Productos:
                            <table class="table table-bordered table-striped" id="productos">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Producto</th>
                                        <th>Código</th>
                                        <th>Costo base (USD)</th>
                                        <th>Costo traspaso (%)</th>
                                        <th>Costo traspaso (USD)</th>
                                        <th>Costo transporte (USD)</th>
                                        <th>Costo final (USD)</th>
                                        <th>Remover</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                        <div class="col d-flex justify-content-end">
                            <h5>Cantidad Total:</h5>
                            &nbsp;
                            <h5 class="text-primary fw-bold" id="productosTotalCantidad">0</h5>
                            &nbsp;
                            <h5>Unidades</h5>
                        </div>

                        <div class="col d-flex justify-content-end">
                            <h5>Costo Base Total (USD):</h5>
                            &nbsp;
                            <h5>$</h5>
                            &nbsp;
                            <h5 class="text-success fw-bold" id="productosCostoBaseTotalUSD">0.00</h5>
                        </div>

                        <div class="col d-flex justify-content-end">
                            <h5>Costo Final Total (USD):</h5>
                            &nbsp;
                            <h5>$</h5>
                            &nbsp;
                            <h5 class="text-warning fw-bold" id="productosCostoFinalTotalUSD">0.00</h5>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="fa-solid fa-duotone fa-close"></i>Cerrar</button>
                    <button type="button" id="btnGuardar" class="btn btn-primary"><i
                            class="fa-solid fa-duotone fa-save"></i>
                        Guardar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#dataTable").DataTable({
                processing: true,
                ajax: {
                    url: "{{ route('abastecimientos.listar') }}", // Ruta de Laravel
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error: function(xhr, error, thrown) {
                        console.error("Error al cargar los datos:", error);
                    }
                },
                columns: [{
                        data: "idAbastecimiento"
                    },
                    {
                        data: "productos",
                        render: function(data, type, row) {
                            if (!data || data.length === 0) {
                                return "-";
                            }

                            return data.map((producto, index) => {
                                const costoFinalUSD = (parseFloat(producto.costoBaseUSD) + (
                                    producto.costoBaseUSD * producto
                                    .traspasoPorcentaje / 100) + parseFloat(producto
                                    .transporteUSD)).toFixed(2);

                                return `<span class="text-primary fw-bold">● ${index + 1}.</span> <span class="text-info fw-bold">${producto.empresa.nombreEmpresa}</span> - ${producto.marca.nombreMarca} ${producto.nombreProducto}:
                                <br>
                                ${producto.codigoProducto} - <span class="text-success fw-bold">Costo base: ${producto.costoBaseUSD} USD</span>, 
                                Traspaso: ${producto.traspasoPorcentaje}% - ${(producto.costoBaseUSD * producto.traspasoPorcentaje / 100).toFixed(2)} USD, 
                                Transporte: ${producto.transporteUSD} USD, 
                                <span class="text-warning fw-bold">Costo Final: ${costoFinalUSD} USD</span>`
                            }).join("<br>");
                        },
                        width: '100%'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            if (!row.productos || row.productos.length === 0) {
                                return "-";
                            }

                            let cantidadTotal = row.productos.length;
                            let costoBaseTotal = 0;
                            let costoFinalTotal = 0;

                            row.productos.forEach((producto) => {
                                // Sumar costo base
                                costoBaseTotal += parseFloat(producto.costoBaseUSD) || 0;

                                // Calcular y sumar costo final
                                const costoFinal = parseFloat(producto.costoBaseUSD) +
                                    (producto.costoBaseUSD * producto.traspasoPorcentaje /
                                        100) +
                                    parseFloat(producto.transporteUSD);
                                costoFinalTotal += costoFinal;
                            });

                            return `
                                <span class="text-info fw-bold">Cantidad total: ${cantidadTotal} productos</span><br>
                                <span class="text-success fw-bold">Costo base total: ${costoBaseTotal.toFixed(2)} USD</span><br>
                                <span class="text-warning fw-bold">Costo final total: ${costoFinalTotal.toFixed(2)} USD</span><br>
                            `;
                        }
                    },
                    {
                        data: "fechaRegistro",
                        render: function(data, type, row) {
                            return new Date(data).toLocaleString();
                        }
                    },
                    {
                        data: "fechaActualizacion",
                        render: function(data, type, row) {
                            return new Date(data).toLocaleString();
                        }
                    },
                    {
                        data: "editor.nombreUsuario",
                        render: function(data, type, row) {
                            return data || '-';
                        }
                    }
                ],
                responsive: true,
                lengthChange: true,
                autoWidth: true,
                colReorder: true,
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                dom: 'Blfrtip',
                buttons: [{
                        extend: 'copy',
                        className: 'btn btn-secondary'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger'
                    },
                    {
                        extend: 'colvis',
                        className: 'btn btn-info'
                    },
                    {
                        extend: 'searchBuilder',
                        className: 'btn btn-warning'
                    },
                ],
                @include('datatables.dataTablesLanguageProperty')
            }).buttons().container().appendTo('#dataTableExportButtonsContainer');

            $(document).on('click', '#btnGuardar', function() {
                const idEmpresa = $('#empresa').val();
                const idMarca = $('#marca').val();
                let productos = [];

                $("#productos tbody tr").each(function() {
                    let fila = $(this);

                    let nombreProducto = fila.find('.nombreProducto').text().trim().toUpperCase();
                    let codigoProducto = fila.find('.codigoProducto').text();
                    let costoBaseUSD = parseFloat(fila.find('.costoBaseUSD').text());
                    let traspasoPorcentaje = parseFloat(fila.find('.traspasoPorcentaje').text());
                    let transporteUSD = parseFloat(fila.find('.transporteUSD').text());

                    productos.push({
                        nombreProducto: nombreProducto,
                        codigoProducto: codigoProducto,
                        costoBaseUSD: costoBaseUSD,
                        traspasoPorcentaje: traspasoPorcentaje,
                        transporteUSD: transporteUSD,
                    });
                });

                /*
                console.log(idEmpresa);
                console.log(idMarca);
                console.log(productos);
                */

                if (productos.length === 0) {
                    Swal.fire({
                        title: "¡No válido!",
                        text: "Debe agregar al menos un producto a la tabla.",
                        icon: "warning"
                    });
                    return;
                }
                $.ajax({
                    url: "{{ route('abastecimientos.create') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        idMarca: idMarca,
                        idEmpresa: idEmpresa,
                        productos: productos
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Éxito', response.message, 'success');
                            $('#modalCreate').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();

                            $("#productos tbody").empty();
                            actualizarTotales();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        //console.error(xhr.responseText);
                        //console.error(JSON.parse(xhr.responseText));

                        const erroresConcatenados = Object.values(JSON.parse(xhr.responseText)
                                .errors)
                            .flatMap(errores => errores)
                            .join('<br>');

                        Swal.fire('Error', 'Ocurrió un error al intentar la acción: <br>' +
                            erroresConcatenados, 'error');
                    }
                });
            });

            $('#empresa').select2({
                language: "es",
                dropdownCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
                selectionCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
                dropdownParent: $('#modalCreate')
            });

            $('#marca').select2({
                language: "es",
                dropdownCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
                selectionCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
                dropdownParent: $('#modalCreate')
            });

            // 1. Agregar con ENTER en el campo cantidad
            $("#cantidad").on("keypress", function(e) {
                if (e.which === 13) { // ENTER
                    e.preventDefault();
                    agregarProducto();
                }
            });

            // También con el botón
            $("#btnAdd").on("click", function() {
                agregarProducto();
            });

            $("#btnEmptyTable").on("click", function() {
                Swal.fire({
                    title: `¡ATENCIÓN!`,
                    text: `¿Estás seguro de vaciar la tabla de productos?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Sí, vaciar`,
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#productos tbody").empty();
                        actualizarTotales();

                        Swal.fire({
                            icon: "success",
                            title: "¡Hecho!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });

            function agregarProducto() {
                let empresa = $("#empresa option:selected").text().trim();
                let marca = $("#marca option:selected").text().trim();
                let nombreProducto = $("#nombreProducto").val().trim();
                let costoBase = parseFloat($("#costoBaseUSD").val());
                let traspasoPorcentaje = parseFloat($("#traspasoPorcentaje").val());
                let transporteUSD = parseFloat($("#transporteUSD").val());
                let cantidad = parseInt($("#cantidad").val());

                if (!empresa || !marca || !nombreProducto || isNaN(costoBase) || isNaN(traspasoPorcentaje) || isNaN(
                        transporteUSD) || isNaN(cantidad)) {
                    Swal.fire({
                        title: "¡No válido!",
                        text: "Complete todos los campos obligatorios.",
                        icon: "warning"
                    });
                    return;
                }

                // punto de inicio: cuántas filas hay ya
                let filasExistentes = $("#productos tbody tr").length;

                // 2. Insertar N filas según cantidad
                for (let i = 0; i < cantidad; i++) {
                    let numeroFila = filasExistentes + i + 1;

                    // 3. Generar código: 2 letras empresa + 2 letras marca + fecha/hora + correlativo
                    let ahora = new Date();
                    let fechaHora = ahora.getFullYear() +
                        String(ahora.getMonth() + 1).padStart(2, "0") +
                        String(ahora.getDate()).padStart(2, "0") + "-" +
                        String(ahora.getHours()).padStart(2, "0") +
                        String(ahora.getMinutes()).padStart(2, "0");

                    let codigo = empresa.substring(0, 2).toUpperCase() + "-" +
                        marca.substring(0, 2).toUpperCase() + "-" +
                        fechaHora + "-" +
                        String(numeroFila).padStart(2, "0");

                    let costoTraspasoUSD = (costoBase * traspasoPorcentaje / 100).toFixed(2);
                    let costoFinalUSD = (costoBase + parseFloat(costoTraspasoUSD) + transporteUSD).toFixed(2);

                    let fila = `
                        <tr>
                            <td class="numero">${numeroFila}</td>
                            <td class="nombreProducto">${nombreProducto.toUpperCase()}</td>
                            <td class="codigoProducto">${codigo}</td>
                            <td class="costoBaseUSD">${costoBase.toFixed(2)}</td>
                            <td class="traspasoPorcentaje">${traspasoPorcentaje.toFixed(2)}</td>
                            <td class="traspasoUSD">${costoTraspasoUSD}</td>
                            <td class="transporteUSD">${transporteUSD.toFixed(2)}</td>
                            <td class="costoFinalUSD">${costoFinalUSD}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm btn-remover" 
                                data-toggle="tooltip" title="Remover de la tabla">
                                <i class="fa-solid fa-duotone fa-trash-can-list"></i>
                                </button>
                            </td>
                        </tr>
                        `;

                    $("#productos tbody").append(fila);
                }

                actualizarTotales();
                limpiarCampos();
            }

            // 4. Actualizar totales
            function actualizarTotales() {
                let totalCantidad = $("#productos tbody tr").length;
                let totalCostoBase = 0;
                let totalCostoFinal = 0;

                $("#productos tbody tr").each(function() {
                    totalCostoBase += parseFloat($(this).find("td:eq(3)").text());
                    totalCostoFinal += parseFloat($(this).find("td:eq(7)").text());
                });

                $("#productosTotalCantidad").text(totalCantidad);
                $("#productosCostoBaseTotalUSD").text(totalCostoBase.toFixed(2));
                $("#productosCostoFinalTotalUSD").text(totalCostoFinal.toFixed(2));
            }

            // Eliminar fila
            $(document).on("click", ".btn-remover", function() {
                $(this).closest("tr").remove();
                reenumerarFilas();
                actualizarTotales();
            });

            function reenumerarFilas() {
                $("#productos tbody tr").each(function(index) {
                    let numero = index + 1;
                    $(this).find(".numero").text(numero);

                    // reconstruir código con nuevo correlativo
                    let codigoActual = $(this).find(".codigoProducto").text();
                    let baseCodigo = codigoActual.slice(0, -2); // quitar correlativo
                    $(this).find(".codigoProducto").text(baseCodigo + String(numero).padStart(2, "0"));
                });
            }

            // Limpiar después de agregar
            function limpiarCampos() {
                $("#cantidad").val("");
                $("#nombreProducto").focus();
            }

        });
    </script>
@endsection
