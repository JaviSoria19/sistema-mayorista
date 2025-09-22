@extends('Layouts.app')

@section('content')
    <h1 class="text-center text-info fw-bold"><i class="fa fa-user-tie"></i> {{ $headTitle }}</h1>

    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCreateOrEdit">
        <i class="fa fa-plus"></i> Crear usuario</button>

    <!-- Modal para crear y editar usuarios -->
    <div class="modal fade" id="modalCreateOrEdit" tabindex="-1" aria-labelledby="modalCreateOrEdit_Title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalCreateOrEdit_Title">modalCreateOrEditTitle</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="fa fa-close"></i>Cerrar</button>
                    <button type="button" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-info fw-bold">Lista de usuarios</h2>

    <div id="miContenedorBotones" class="mb-3"></div>

    <table class="table table-bordered table-striped" id="dataTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre Usuario</th>
                <th>Empleado</th>
                <th>Estado</th>
                <th>F. Registro</th>
                <th>F. Actualización</th>
                <th>Modificado Por</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>
@endsection

@section('scripts')
    <script>
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
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // número de iteración
                        }
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
                                data-id="${row.idUsuario}" data-toggle="tooltip" title="Editar">
                            <i class="fa-duotone fa-solid fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-${row.estado == 1 ? 'danger' : 'success'} btn-sm btn-cambiar-estado" 
                                data-id="${row.idUsuario}" data-estado="${row.estado}" data-nombre="${row.nombreUsuario}" 
                                data-toggle="tooltip" title="${row.estado == 1 ? 'Desactivar' : 'Restaurar'}">
                            <i class="fa-duotone fa-solid fa-toggle-${row.estado == 1 ? 'off' : 'on'}"></i>
                        </button>
                    </div>
                `;
                        }
                    }
                ],
                @include('datatables.dataTablesGlobalProperties')
                @include('datatables.dataTablesLanguageProperty')
            }).buttons().container().appendTo('#miContenedorBotones');

            $(document).on('click', '.btn-editar', function() {

            });

            $(document).on('click', '.btn-cambiar-estado', function() {
                const id = $(this).data('id');
                const estadoActual = $(this).data('estado');
                const nuevoEstado = estadoActual == 1 ? 0 : 1;
                const nombre = $(this).data('nombre');
                const accion = nuevoEstado == 1 ? 'restaurar' : 'desactivar';

                Swal.fire({
                    title: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} usuario?`,
                    text: `¿Estás seguro de ${accion} el usuario ${nombre}?`,
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
