@extends('layouts.app')

@section('content')
    <h1 class="text-center text-info fw-bold"><i class="fa-solid fa-duotone fa-file-pen"></i>
        {{ $headTitle }}</h1>

    <button type="button" class="btn btn-success mb-3 btn-crear" data-bs-toggle="modal" data-bs-target="#modalCreateOrEdit">
        <i class="fa-solid fa-duotone fa-plus"></i> Crear pedido a empresa</button>

    <h2 class="text-info fw-bold">Lista de pedidos a empresas</h2>

    <div class="card p-3 mb-3">
        <p>Seleccione una opción para <i class="fa-solid fa-duotone fa-file-export"></i> exportar o <i
                class="fa-solid fa-duotone fa-filter"></i> filtrar la tabla:</p>
        <div id="dataTableExportButtonsContainer"></div>
    </div>

    <table class="table table-bordered table-striped" id="dataTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Empresa</th>
                <th>Detalles</th>
                <th>Total (USD)</th>
                <th>Estado</th>
                <th>F. Registro</th>
                <th>F. Actualización</th>
                <th>Modificado Por</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>

    <div class="mb-3"></div>

    <!-- Modal para crear y editar pedidos-empresas -->
    <div class="modal fade" id="modalCreateOrEdit" tabindex="-1" aria-labelledby="modalCreateOrEdit_Title"
        aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!--modal-lg | modal-xl | modal-fullscreen-->
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalCreateOrEdit_Title"><i class="fa-solid fa-duotone fa-plus"></i>
                        CREAR USUARIO</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCreateOrEdit">
                        <!-- input de idPedidoEmpresa en caso de editar -->
                        <input type="hidden" name="idPedidoEmpresa" value="0">

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
                            <label for="nombreProducto" class="form-label">Producto <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombreProducto" name="nombreProducto"
                                list="productos" required>
                        </div>

                        <datalist id="productos">
                            <option>SAMSUNG S23 ULTRA 8/256 NEGRO</option>
                            <option>SAMSUNG A05 4/128 BLANCO</option>
                            <option>REALME 12 PRO PLUS 12/512 AZUL</option>
                        </datalist>

                        <div class="mb-3">
                            <label for="precioUSD" class="form-label">Precio (USD) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="precioUSD" name="precioUSD" required>
                        </div>

                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad (Unidades) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" step="1"
                                pattern="\d*" placeholder="Solo números enteros" required>
                        </div>

                        <button type="button" id="btnAdd" class="btn btn-success mb-3"><i
                                class="fa-solid fa-duotone fa-plus"></i>
                            Añadir a la lista</button>

                        <div class="mb-3">
                            Detalles:
                            <table class="table table-bordered table-striped" id="detalles">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Producto</th>
                                        <th>Precio (USD)</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal (USD)</th>
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
                            <h5 class="text-primary fw-bold" id="detallesTotalCantidad">0</h5>
                            &nbsp;
                            <h5>Unidades</h5>
                        </div>

                        <div class="col d-flex justify-content-end">
                            <h5>Monto Total (USD):</h5>
                            &nbsp;
                            <h5>$</h5>
                            &nbsp;
                            <h5 class="text-primary fw-bold" id="detallesTotalUSD">0.00</h5>
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
                    url: "{{ route('pedidos-empresas.listar') }}", // Ruta de Laravel
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error: function(xhr, error, thrown) {
                        console.error("Error al cargar los datos:", error);
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // número de iteración
                        }
                    },
                    {
                        data: "empresa.nombreEmpresa",
                    },
                    {
                        data: "detalles",
                        render: function(data, type, row) {
                            if (!data || data.length === 0) {
                                return "-";
                            }

                            return data.map((detalle, index) =>
                                `${index + 1}. ${detalle.nombreProducto}<br>
                                (${detalle.cantidad} uds. * ${detalle.precioUSD} USD = ${(detalle.cantidad * detalle.precioUSD).toFixed(2)} USD)`
                            ).join("<br>");
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            if (!row.detalles || row.detalles.length === 0) {
                                return "0.00";
                            }
                            let total = row.detalles.reduce((acc, detalle) => {
                                return acc + (detalle.cantidad * parseFloat(detalle
                                    .precioUSD));
                            }, 0);
                            return total.toFixed(2);
                        }
                    },
                    {
                        data: "estado",
                        render: function(data, type, row) {
                            if (data == 1) {
                                return '<span class="badge bg-success">Activo</span>';
                            } else {
                                return '<span class="badge bg-danger">Archivado</span>';
                            }
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
                            return data ? data : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-warning btn-sm btn-editar" 
                                data-id="${row.idPedidoEmpresa}" data-toggle="tooltip" title="Editar">
                            <i class="fa-duotone fa-solid fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-${row.estado == 1 ? 'danger' : 'success'} btn-sm btn-cambiar-estado" 
                                data-id="${row.idPedidoEmpresa}" data-estado="${row.estado}" data-nombre="${row.empresa.nombreEmpresa}" 
                                data-toggle="tooltip" title="${row.estado == 1 ? 'Deshabilitar' : 'Habilitar'}">
                            <i class="fa-duotone fa-solid fa-toggle-${row.estado == 1 ? 'off' : 'on'}"></i>
                        </button>
                    </div>
                `;
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

            $(document).on('click', '.btn-crear', function() {
                const id = 0;
                $('#formCreateOrEdit input[name="idPedidoEmpresa"]').val(0);
                $('#formCreateOrEdit select[name="idEmpresa"]').val('')
                    .trigger('change');
                $('#formCreateOrEdit input[name="montoUSD"]').val(0);
                $('#formCreateOrEdit input[name="pagoUSD"]').val(0);

                const titleElement = document.getElementById('modalCreateOrEdit_Title');
                titleElement.innerHTML =
                    '<i class="fa-solid fa-duotone fa-plus"></i> CREAR PEDIDO A EMPRESA';
                $('#modalCreateOrEdit').modal('show');
            });



            $(document).on('click', '.btn-editar', function() {
                const id = $(this).data('id');

                $.get("{{ route('pedidos-empresas.index') . '/' }}" + id, function(saldo_empresa) {
                    console.log(saldo_empresa);
                    $('#formCreateOrEdit input[name="idPedidoEmpresa"]').val(saldo_empresa.data
                        .idPedidoEmpresa);
                    $('#formCreateOrEdit select[name="idEmpresa"]').val(saldo_empresa.data
                            .idEmpresa)
                        .trigger('change');
                    $('#formCreateOrEdit input[name="montoUSD"]').val(saldo_empresa.data.montoUSD);
                    $('#formCreateOrEdit input[name="pagoUSD"]').val(saldo_empresa.data.pagoUSD);

                    const titleElement = document.getElementById('modalCreateOrEdit_Title');
                    titleElement.innerHTML =
                        '<i class="fa-solid fa-duotone fa-edit"></i> EDITAR PEDIDO A EMPRESA';
                    $('#modalCreateOrEdit').modal('show');
                });
            });


            $(document).on('click', '#btnGuardar', function() {
                const idPedidoEmpresa = $('#formCreateOrEdit input[name="idPedidoEmpresa"]').val();
                const url = idPedidoEmpresa == 0 ?
                    "{{ route('pedidos-empresas.create') }}" // POST -> crear
                    :
                    "{{ route('pedidos-empresas.index') . '/' }}" + idPedidoEmpresa; // PUT -> actualizar

                const type = idPedidoEmpresa == 0 ? 'POST' : 'PUT';

                $.ajax({
                    url: url,
                    type: type,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $('#formCreateOrEdit').serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Éxito', response.message, 'success');
                            $('#modalCreateOrEdit').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
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



            $(document).on('click', '.btn-cambiar-estado', function() {
                const id = $(this).data('id');
                const estadoActual = $(this).data('estado');
                const nuevoEstado = estadoActual == 1 ? 0 : 1;
                const nombre = $(this).data('nombre');
                const accion = nuevoEstado == 1 ? 'restaurar' : 'archivar';

                Swal.fire({
                    title: `¡ATENCIÓN!`,
                    text: `¿Estás seguro de ${accion} el pedido a la empresa ${nombre}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Sí, ${accion}`,
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('pedidos-empresas.index') . '/' }}" + id,
                            type: "PATCH",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                idPedidoEmpresa: id
                            },
                            success: function(response) {
                                Swal.fire('Actualizado', response.message, 'success');
                                $('#dataTable').DataTable().ajax.reload();
                            },
                            error: function() {
                                Swal.fire('Error',
                                    `No se pudo ${accion} el pedido a empresa`,
                                    'error');
                            }
                        });

                    }
                });
            });
        });

        $(document).ready(function() {
            $('#empresa').select2({
                language: "es",
                dropdownCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
                selectionCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
                dropdownParent: $('#modalCreateOrEdit')
            });
        });

        //Scripts de la operación CREATE
        $(document).ready(function() {
            function agregarProducto() {
                let nombre = $("#nombreProducto").val().trim();
                let precio = parseFloat($("#precioUSD").val());
                let cantidad = parseInt($("#cantidad").val());

                if (nombre !== "" && !isNaN(precio) && !isNaN(cantidad) && precio > 0 && cantidad > 0) {
                    let fila = `
                <tr>
                    <td class="numero"></td>
                    <td contenteditable="true">${nombre}</td>
                    <td class="precio" contenteditable="true">${precio.toFixed(2)}</td>
                    <td class="cantidad" contenteditable="true">${cantidad}</td>
                    <td class="subTotalUSD">${(precio * cantidad).toFixed(2)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-remover" 
                            data-toggle="tooltip" title="Remover de la lista">
                            <i class="fa-solid fa-duotone fa-trash-can-list"></i>
                        </button>
                    </td>
                </tr>
            `;
                    $("#detalles tbody").append(fila);

                    reenumerarFilas();
                    actualizarTotales();

                    // limpiar inputs
                    //$("#nombreProducto").val("");
                    //$("#precioUSD").val("");
                    $("#cantidad").val("");
                    $("#nombreProducto").focus();
                }
            }

            function actualizarTotales() {
                let totalUnidades = 0;
                let totalUSD = 0;

                $("#detalles tbody tr").each(function() {
                    let precio = parseFloat($(this).find(".precio").text());
                    let cantidad = parseInt($(this).find(".cantidad").text());

                    if (isNaN(precio)) precio = 0;
                    if (isNaN(cantidad)) cantidad = 0;

                    // actualizar subtotal de la fila
                    let subTotal = precio * cantidad;
                    $(this).find(".subTotalUSD").text(subTotal.toFixed(2));

                    // acumular totales
                    totalUnidades += cantidad;
                    totalUSD += subTotal;
                });

                $("#detallesTotalCantidad").text(totalUnidades);
                $("#detallesTotalUSD").text(totalUSD.toFixed(2));
            }

            function reenumerarFilas() {
                $("#detalles tbody tr").each(function(index) {
                    $(this).find(".numero").text(index + 1);
                });
            }

            // Botón añadir
            $("#btnAdd").on("click", function() {
                agregarProducto();
            });

            // Enter en cualquier input
            $("#nombreProducto, #precioUSD, #cantidad").on("keypress", function(e) {
                if (e.which === 13) { // Enter
                    e.preventDefault();
                    agregarProducto();
                }
            });

            // Remover fila
            $("#detalles").on("click", ".btn-remover", function() {
                $(this).closest("tr").remove();
                reenumerarFilas();
                actualizarTotales();
            });

            // Detectar cambios en precio o cantidad (cuando usuario edita)
            $("#detalles").on("input", ".precio, .cantidad", function() {
                let valor = $(this).text();

                // Validar que sea numérico
                if (isNaN(valor) || valor.trim() === "") {
                    $(this).text("0");
                }

                actualizarTotales();
            });
        });
    </script>
@endsection
