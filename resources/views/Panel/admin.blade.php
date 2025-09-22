@extends('layouts.app')

@section('content')
    <h1 class="text-center">{{ $headTitle }}</h1>
    <p class="text-center">Bienvenido, {{ session('nombreUsuario') }}</p>
    En esta ventana se pretende colocar varios elementos que resuman diversos datos del sistema.
    <br />
    <button class="btn btn-info">Test</button>
    <br /><br />

    <h5 class="card-title">Gestión de Usuarios</h5>
    <br />

    <select id="select2" class="form-control">
        <option>ALEMANIA</option>
        <option>BRASIL</option>
        <option>COLOMBIA</option>
        <option>DINAMARCA</option>
    </select>

    <div id="miContenedorBotones" class="mb-3"></div>

    <table class="table table-bordered table-striped" id="dataTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Usuario</th>
                <th>Empleado</th>
                <th>Estado</th>
                <th>Fecha Registro</th>
                <th>Última Actualización</th>
                <th>Modificado Por</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            Swal.fire({
                title: 'Error!',
                text: 'Do you want to continue',
                icon: 'error',
                confirmButtonText: 'Cool'
            });
        });

        $(document).ready(function() {
            $("#dataTable").DataTable({
                processing: true,
                ajax: {
                    url: "{{ route('usuarios.listar') }}", // Ruta de Laravel
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error: function(xhr, error, thrown) {
                        console.error("Error al cargar los datos:", error);
                    },
                    data: function(d) {
                        return d;
                    }
                },
                columns: [{
                        data: "idUsuario",
                    },
                    {
                        data: "nombreUsuario",
                    },
                    {
                        data: "empleado.nombreEmpleado",
                    },
                    {
                        data: "estado",
                        render: function(data, type, row) {
                            if (data == 1) {
                                return '<span class="badge bg-success">Activo</span>';
                            } else {
                                return '<span class="badge bg-danger">Inactivo</span>';
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
                        data: "modificadoPor",
                        render: function(data, type, row) {
                            return data ? data : 'N/A';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-sm btn-editar" 
                                data-id="${row.idUsuario}" data-toggle="tooltip" title="Editar">
                            <i class="fa-duotone fa-solid fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-info btn-sm btn-ver" 
                                data-id="${row.idUsuario}" data-toggle="tooltip" title="Ver detalles">
                            <i class="fa-duotone fa-solid fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm btn-eliminar" 
                                data-id="${row.idUsuario}" data-toggle="tooltip" title="Eliminar">
                            <i class="fa-duotone fa-solid fa-trash"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-sm btn-cambiar-estado" 
                                data-id="${row.idUsuario}" data-estado="${row.estado}" 
                                data-toggle="tooltip" title="Cambiar Estado">
                            <i class="fa-duotone fa-solid fa-toggle-${row.estado == 1 ? 'on' : 'off'}"></i>
                        </button>
                    </div>
                `;
                        }
                    }
                ],
                // la propiedad dom permite colocar los botones en el contenedor personalizado
                responsive: true,
                lengthChange: true,
                autoWidth: true,
                colReorder: true,
                order: [],
                pageLength: 10,
                dom: 'Bfrtip',
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
                language: {
                    "decimal": "",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "<i class=\"fa-duotone fa-solid fa-search\"></i> Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna ascendente",
                        "sortDescending": ": activar para ordenar la columna descendente"
                    },
                    "buttons": {
                        "copy": "<i class=\"fa-duotone fa-solid fa-copy\"></i> Copiar",
                        "colvis": "<i class=\"fa-duotone fa-solid fa-eye\"></i> Visibilidad de columnas",
                        "collection": "Colección",
                        "colvisRestore": "Restaurar visibilidad",
                        "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
                        "copySuccess": {
                            "1": "Copiada 1 fila al portapapeles",
                            "_": "Copiadas %ds filas al portapapeles"
                        },
                        "copyTitle": "Copiar al portapapeles",
                        "csv": "<i class=\"fa-duotone fa-solid fa-file-csv\"></i> CSV",
                        "excel": "<i class=\"fa-duotone fa-solid fa-file-excel\"></i> Excel",
                        "pageLength": {
                            "-1": "Mostrar todas las filas",
                            "_": "Mostrar %d filas"
                        },
                        "pdf": "<i class=\"fa-duotone fa-solid fa-file-pdf\"></i> PDF",
                        "print": "<i class=\"fa-duotone fa-solid fa-file-print\"></i> Imprimir",
                        "renameState": "Renombrar",
                        "updateState": "Actualizar",
                        "createState": "Crear Estado",
                        "removeAllStates": "Remover Estados",
                        "removeState": "Remover",
                        "savedStates": "Estados Guardados",
                        "stateRestore": "Estado %d"
                    },
                    "searchBuilder": {
                        "add": "Agregar condición",
                        "button": {
                            "0": "<i class=\"fa-duotone fa-solid fa-filter\"></i> Constructor de búsqueda",
                            "_": "<i class=\"fa-duotone fa-solid fa-filter\"></i> Constructor de búsqueda (%d)"
                        },
                        "clearAll": "Limpiar todo",
                        "condition": "Condición",
                        "conditions": {
                            "date": {
                                "after": "Después",
                                "before": "Antes",
                                "between": "Entre",
                                "empty": "Vacío",
                                "equals": "Igual a",
                                "notBetween": "No entre",
                                "notEmpty": "No vacío",
                                "not": "Diferente de"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vacío",
                                "equals": "Igual a",
                                "gt": "Mayor a",
                                "gte": "Mayor o igual a",
                                "lt": "Menor que",
                                "lte": "Menor o igual a",
                                "notBetween": "No entre",
                                "notEmpty": "No vacío",
                                "not": "Diferente de"
                            },
                            "string": {
                                "contains": "Contiene",
                                "empty": "Vacío",
                                "endsWith": "Termina con",
                                "equals": "Igual a",
                                "notEmpty": "No vacío",
                                "startsWith": "Comienza con",
                                "not": "Diferente de",
                                "notContains": "No contiene",
                                "notStartsWith": "No comienza con",
                                "notEndsWith": "No termina con"
                            },
                            "array": {
                                "not": "Diferente de",
                                "equals": "Igual a",
                                "empty": "Vacío",
                                "contains": "Contiene",
                                "notEmpty": "No vacío",
                                "without": "Sin"
                            }
                        },
                        "data": "Datos",
                        "deleteTitle": "Eliminar regla de filtrado",
                        "leftTitle": "Criterios anulados",
                        "logicAnd": "Y",
                        "logicOr": "O",
                        "rightTitle": "Criterios de sangría",
                        "title": {
                            "0": "Constructor de búsqueda",
                            "_": "Constructor de búsqueda (%d)"
                        },
                        "value": "Valor"
                    },
                    "searchPanes": {
                        "clearMessage": "Limpiar todo",
                        "collapse": {
                            "0": "Paneles de búsqueda",
                            "_": "Paneles de búsqueda (%d)"
                        },
                        "count": "{total}",
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Sin paneles de búsqueda",
                        "loadMessage": "Cargando paneles de búsqueda",
                        "title": "Filtros activos - %d",
                        "showMessage": "Mostrar todos",
                        "collapseMessage": "Colapsar todos"
                    }
                }

            }).buttons().container().appendTo('#miContenedorBotones');

            $(document).on('click', '.btn-editar', function() {
                
            });

            $(document).on('click', '.btn-ver', function() {
                
            });

            $(document).on('click', '.btn-eliminar', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enviar petición DELETE
                        $.ajax({
                            url: `/usuarios/${id}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire('Eliminado', 'El usuario ha sido eliminado',
                                    'success');
                                $('#dataTable').DataTable().ajax.reload();
                            },
                            error: function() {
                                Swal.fire('Error', 'No se pudo eliminar el usuario',
                                    'error');
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.btn-cambiar-estado', function() {
                const id = $(this).data('id');
                const estadoActual = $(this).data('estado');
                const nuevoEstado = estadoActual == 1 ? 0 : 1;
                const accion = nuevoEstado == 1 ? 'activar' : 'desactivar';

                Swal.fire({
                    title: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} usuario?`,
                    text: `¿Estás seguro de ${accion} este usuario?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Sí, ${accion}`,
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/usuarios/${id}/cambiar-estado`,
                            type: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                estado: nuevoEstado
                            },
                            success: function(response) {
                                Swal.fire('Actualizado',
                                    `Usuario ${accion}do correctamente`, 'success');
                                $('#dataTable').DataTable().ajax.reload();
                            },
                            error: function() {
                                Swal.fire('Error', `No se pudo ${accion} el usuario`,
                                    'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
