@extends('layouts.app')

@section('content')
    <h1 class="text-center text-info fw-bold"><i class="fa-solid fa-duotone fa-sliders"></i> {{ $headTitle }}</h1>

    <div class="card p-3 mb-3">
        <div class="row">
            <div class="col-4"></div>

            <div class="col-4">
                <form id="formCreateOrEdit">
                    <!-- input de idEmpleado en caso de editar -->
                    <input type="hidden" name="idEmpleado" value="0">

                    <div class="mb-3">
                        <label for="paramPorcentajeTraspaso" class="form-label">Porcentaje de Traspaso: <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="paramPorcentajeTraspaso"
                            name="paramPorcentajeTraspaso" value="{{ $parametro->paramPorcentajeTraspaso }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="paramTransporteUSD" class="form-label">Transporte USD: <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="paramTransporteUSD" name="paramTransporteUSD"
                            value="{{ $parametro->paramTransporteUSD }}" required>
                    </div>
                </form>
                <p class="fw-bold">
                    <i class="fa-solid fa-duotone fa-circle-info"></i> Última modificación: <span
                        class="text-info">{{ $parametro->editor->nombreUsuario ? $parametro->editor->nombreUsuario : '-' }}</span>
                </p>
                <button type="button" id="btnGuardar" class="btn btn-primary"><i class="fa-solid fa-duotone fa-save"></i>
                    Guardar</button>
            </div>

            <div class="col-4"></div>
        </div>
    </div>
    <div class="mb-3"></div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $(document).on('click', '#btnGuardar', function() {
                const url = "{{ route('parametros.index') . '/' }}" + 1;
                $.ajax({
                    url: "{{ route('parametros.index') . '/' }}" + 1,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $('#formCreateOrEdit').serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Éxito', response.message, 'success');
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
        });
    </script>
@endsection
