@extends('layouts.app')

@section('content')
    <h1 class="text-center text-info fw-bold"><i class="fa-solid fa-duotone fa-boxes-stacked"></i>
        {{ $headTitle }}</h1>

    <div class="mb-3">
        <a class="btn btn-success" href="{{ route('abastecimientos.create') }}"><i class="fa-solid fa-duotone fa-plus"></i>
            Crear abastecimiento</a>
        <button type="button" class="btn btn-success btn-crear" data-bs-toggle="modal" data-bs-target="#modalCreateOrEdit">
            <i class="fa-solid fa-duotone fa-plus"></i> Crear producto</button>
    </div>

    <h2 class="text-info fw-bold">Lista de productos</h2>

    <div class="card p-3 mb-3">
        <p>Seleccione una opción para <i class="fa-solid fa-duotone fa-file-export"></i> exportar o <i
                class="fa-solid fa-duotone fa-filter"></i> filtrar la tabla:</p>
        <div id="dataTableExportButtonsContainer"></div>
    </div>

    <table class="table table-bordered table-striped" id="dataTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Nro. Abastecimiento</th>
                <th>Empresa</th>
                <th>Marca</th>
                <th>Producto</th>
                <th>Código</th>
                <th>Costo base (USD)</th>
                <th>Costo traspaso (%)</th>
                <th>Costo traspaso (USD)</th>
                <th>Costo transporte (USD)</th>
                <th>Costo final (USD)</th>
                <th>Estado</th>
                <th>F. Registro</th>
                <th>F. Actualización</th>
                <th>F. Eliminación</th>
                <th>F. Venta</th>
                <th>Modificado Por</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>

    <div class="mb-3"></div>

    <!-- Modal para crear y editar productos -->
    <div class="modal fade" id="modalCreateOrEdit" tabindex="-1" aria-labelledby="modalCreateOrEdit_Title"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalCreateOrEdit_Title"><i class="fa-solid fa-duotone fa-plus"></i>
                        CREAR USUARIO</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCreateOrEdit">
                        <!-- input de idProducto en caso de editar -->
                        <input type="hidden" name="idProducto" value="0">

                        <div class="mb-3">
                            <label for="abastecimiento" class="form-label">Abastecimiento <span
                                    class="text-danger">*</span></label><br>
                            <select style="width: 100%" class="form-select" id="abastecimiento" name="idAbastecimiento"
                                required>
                                <option value="" disabled selected>Seleccione el número de abastecimiento</option>
                                @foreach ($abastecimientos as $abastecimiento)
                                    <option>{{ $abastecimiento->idAbastecimiento }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="empresa" class="form-label">Empresa <span class="text-danger">*</span></label><br>
                            <select style="width: 100%" class="form-select" id="empresa" name="idEmpresa" required>
                                <option value="" disabled selected>Seleccione una empresa</option>
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
                            <label for="nombreProducto" class="form-label">Nombre de producto <span
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
                            <label for="codigoProducto" class="form-label">Código de producto <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="codigoProducto" name="codigoProducto"
                                placeholder="GENERADO AUTOMÁTICAMENTE AL CREAR" required readonly>
                        </div>

                        <div class="mb-3">
                            <label for="costoBaseUSD" class="form-label">Costo base (USD) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="costoBaseUSD" name="costoBaseUSD" required>
                        </div>

                        <div class="mb-3">
                            <label for="traspasoPorcentaje" class="form-label">Costo traspaso (%) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="traspasoPorcentaje" name="traspasoPorcentaje"
                                value="{{ $parametro->paramPorcentajeTraspaso }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="transporteUSD" class="form-label">Costo transporte (USD) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="transporteUSD" name="transporteUSD"
                                value="{{ $parametro->paramTransporteUSD }}" required>
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
                    url: "{{ route('productos.listar') }}", // Ruta de Laravel
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
                        data: "idAbastecimiento",
                        render: function(data, type, row) {
                            return `<b class="text-primary">${data}</b>`;
                        }
                    },
                    {
                        data: "empresa.nombreEmpresa",
                        render: function(data, type, row) {
                            return `<b class="text-info">${data}</b>`;
                        }
                    },
                    {
                        data: "marca.nombreMarca",
                    },
                    {
                        data: "nombreProducto",
                    },
                    {
                        data: "codigoProducto",
                    },
                    {
                        data: "costoBaseUSD",
                        render: function(data, type, row) {
                            return `<b class="text-success">${data}</b>`;
                        }
                    },
                    {
                        data: "traspasoPorcentaje",
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let traspasoUSD = (row.costoBaseUSD * row.traspasoPorcentaje / 100)
                                .toFixed(2);
                            return traspasoUSD;
                        }
                    },
                    {
                        data: "transporteUSD",
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let costoFinalUSD = parseFloat(row.costoBaseUSD) + parseFloat(row
                                .costoBaseUSD * row.traspasoPorcentaje / 100) + parseFloat(row
                                .transporteUSD);
                            return `<b class="text-warning">${costoFinalUSD.toFixed(2)}</b>`;
                        }
                    },
                    {
                        data: "estado",
                        render: function(data, type, row) {
                            if (data == 1) {
                                return '<span class="badge bg-success">Disponible</span>';
                            } else if (data == 2) {
                                return '<span class="badge bg-primary">Vendido</span>';
                            } else {
                                return '<span class="badge bg-secondary">Eliminado</span>';
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
                        data: "fechaEliminacion",
                        render: function(data, type, row) {
                            return data ? new Date(data).toLocaleString() : '-';
                        }
                    },
                    {
                        data: "fechaVenta",
                        render: function(data, type, row) {
                            return data ? new Date(data).toLocaleString() : '-';
                        }
                    },
                    {
                        data: "editor.nombreUsuario",
                        render: function(data, type, row) {
                            return data || '-';
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
                                            data-id="${row.idProducto}" data-toggle="tooltip" title="Editar">
                                        <i class="fa-duotone fa-solid fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-${row.estado == 1 ? 'danger' : 'success'} btn-sm btn-cambiar-estado" 
                                            data-id="${row.idProducto}" data-estado="${row.estado}" data-nombre="${row.codigoProducto + ' ' + row.nombreProducto}" 
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
                $('#formCreateOrEdit input[name="idProducto"]').val(0);
                $('#formCreateOrEdit select[name="idEmpresa"]').val('').trigger('change');
                $('#formCreateOrEdit select[name="idMarca"]').val('').trigger('change');
                $('#formCreateOrEdit select[name="idAbastecimiento"]').val('').trigger('change');
                $('#formCreateOrEdit input[name="nombreProducto"]').val('');
                $('#formCreateOrEdit input[name="codigoProducto"]').val('');
                $('#formCreateOrEdit input[name="costoBaseUSD"]').val(0);
                $('#formCreateOrEdit input[name="traspasoPorcentaje"]').val(
                    {{ $parametro->paramPorcentajeTraspaso }});
                $('#formCreateOrEdit input[name="transporteUSD"]').val(
                    {{ $parametro->paramTransporteUSD }});

                const titleElement = document.getElementById('modalCreateOrEdit_Title');
                titleElement.innerHTML =
                    '<i class="fa-solid fa-duotone fa-plus"></i> CREAR PRODUCTO';
                $('#modalCreateOrEdit').modal('show');
            });



            $(document).on('click', '.btn-editar', function() {
                const id = $(this).data('id');

                $.get("{{ route('productos.index') . '/' }}" + id, function(producto) {
                    $('#formCreateOrEdit input[name="idProducto"]').val(producto.data.idProducto);
                    $('#formCreateOrEdit select[name="idEmpresa"]').val(producto.data.idEmpresa)
                        .trigger('change');
                    $('#formCreateOrEdit select[name="idMarca"]').val(producto.data.idMarca)
                        .trigger('change');
                    $('#formCreateOrEdit select[name="idAbastecimiento"]').val(producto.data
                        .idAbastecimiento).trigger('change');
                    $('#formCreateOrEdit input[name="nombreProducto"]').val(producto.data
                        .nombreProducto);
                    $('#formCreateOrEdit input[name="codigoProducto"]').val(producto.data
                        .codigoProducto);
                    $('#formCreateOrEdit input[name="costoBaseUSD"]').val(producto.data
                        .costoBaseUSD);
                    $('#formCreateOrEdit input[name="traspasoPorcentaje"]').val(producto.data
                        .traspasoPorcentaje);
                    $('#formCreateOrEdit input[name="transporteUSD"]').val(producto.data
                        .transporteUSD);

                    const titleElement = document.getElementById('modalCreateOrEdit_Title');
                    titleElement.innerHTML =
                        '<i class="fa-solid fa-duotone fa-edit"></i> EDITAR PRODUCTO';
                    $('#modalCreateOrEdit').modal('show');
                });
            });


            $(document).on('click', '#btnGuardar', function() {
                const idProducto = $('#formCreateOrEdit input[name="idProducto"]').val();
                const url = idProducto == 0 ?
                    "{{ route('productos.create') }}" // POST -> crear
                    :
                    "{{ route('productos.index') . '/' }}" + idProducto; // PUT -> actualizar

                const type = idProducto == 0 ? 'POST' : 'PUT';

                if (idProducto == 0) {
                    let empresa = $("#empresa option:selected").text().trim();
                    let marca = $("#marca option:selected").text().trim();
                    let ahora = new Date();
                    /*let fechaHora = ahora.getFullYear() +
                        String(ahora.getMonth() + 1).padStart(2, "0") +
                        String(ahora.getDate()).padStart(2, "0") + "-" +
                        String(ahora.getHours()).padStart(2, "0") +
                        String(ahora.getMinutes()).padStart(2, "0");*/
                    let fechaHora = ahora.getFullYear() +
                        String(ahora.getMonth() + 1).padStart(2, "0") +
                        String(ahora.getDate()).padStart(2, "0");

                    function generarId() {
                        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                        let result = '';

                        for (let i = 0; i < 4; i++) {
                            result += chars.charAt(Math.floor(Math.random() * chars.length));
                        }

                        return result;
                    }

                    /*let codigo = empresa.substring(0, 2).toUpperCase() + "-" +
                        marca.substring(0, 2).toUpperCase() + "-" +
                        fechaHora + "-" + generarId();*/

                    let codigo = fechaHora + "-" + generarId();

                    $('#formCreateOrEdit input[name="codigoProducto"]').val(codigo);
                }

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
                const accion = nuevoEstado == 1 ? 'restaurar' : 'eliminar';

                Swal.fire({
                    title: `¡ATENCIÓN!`,
                    html: `¿Estás seguro de ${accion} el producto <b class="text-primary">${nombre}</b>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Sí, ${accion}`,
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('productos.index') . '/' }}" + id,
                            type: "PATCH",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                idProducto: id
                            },
                            success: function(response) {
                                Swal.fire('Actualizado', response.message, 'success');
                                $('#dataTable').DataTable().ajax.reload();
                            },
                            error: function() {
                                Swal.fire('Error', `No se pudo ${accion} el producto`,
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
            $('#marca').select2({
                language: "es",
                dropdownCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
                selectionCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
                dropdownParent: $('#modalCreateOrEdit')
            });
            $('#abastecimiento').select2({
                language: "es",
                dropdownCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
                selectionCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
                dropdownParent: $('#modalCreateOrEdit')
            });
        });
    </script>
@endsection
