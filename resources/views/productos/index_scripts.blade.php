<!-- DYMO connect framework  -->
<script src="{{ asset('/public/dependencies/dymo-connect-framework/dymo.connect.framework.full.js') }}"></script>
<script>
    $(document).ready(function() {
        dymo.label.framework.init(); //Initialize DYMO Label Framework
        const dymo_entorno = dymo.label.framework.checkEnvironment();

        if (!dymo_entorno.isFrameworkInstalled) {
            Swal.fire({
                theme: 'auto',
                title: "¡Atención!",
                text: "Por favor instale DYMO Label Software (DLS) o DYMO Connect for Desktop (DCD), caso contrario no se podrán imprimir etiquetas con el código de producto.",
                icon: "error"
            });
        }

        if (!dymo_entorno.isWebServicePresent) {
            Swal.fire({
                theme: 'auto',
                title: "¡Atención!",
                text: "Por favor inicie la aplicación DYMO Label Software (DLS) o DYMO Connect for Desktop (DCD) para poder imprimir",
                icon: "error"
            });
        }

        const dymo_printers = dymo.label.framework.getPrinters();

        if (dymo_printers.length === 0) {
            Swal.fire({
                theme: 'auto',
                title: "¡Atención!",
                text: "No hay impresoras DYMO instaladas. Por favor instale una impresora DYMO para poder imprimir etiquetas con el código de producto.",
                icon: "warning",
            });
        } else {
            console.log("Impresoras DYMO encontradas:");
            dymo_printers.forEach(function(printer) {
                console.log(
                    `- ${printer.name} (${printer.modelName}) - ${printer.isConnected ? 'Conectada' : 'Desconectada'}`
                );
            });
        }

        var dymo_labelXml = `<?xml version="1.0" encoding="utf-8"?>
<DieCutLabel Version="8.0" Units="twips">
	<PaperOrientation>Portrait</PaperOrientation>
	<Id>Small30332</Id>
	<PaperName>30332 1 in x 1 in</PaperName>
	<DrawCommands>
		<RoundRectangle X="0" Y="0" Width="1440" Height="1440" Rx="180" Ry="180" />
	</DrawCommands>
	<ObjectInfo>
		<TextObject>
			<Name>lblCodigoProducto</Name>
			<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
			<BackColor Alpha="0" Red="255" Green="255" Blue="255" />
			<LinkedObjectName></LinkedObjectName>
			<Rotation>Rotation0</Rotation>
			<IsMirrored>False</IsMirrored>
			<IsVariable>False</IsVariable>
			<HorizontalAlignment>Center</HorizontalAlignment>
			<VerticalAlignment>Middle</VerticalAlignment>
			<TextFitMode>AlwaysFit</TextFitMode>
			<UseFullFontHeight>True</UseFullFontHeight>
			<Verticalized>False</Verticalized>
			<StyledText>
				<Element>
					<String>PL1-1-1</String>
					<Attributes>
						<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
						<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
					</Attributes>
				</Element>
			</StyledText>
		</TextObject>
		<Bounds X="344.621544893498" Y="252.042954757161" Width="763.056631892695" Height="170.611028315945" />
	</ObjectInfo>
	<ObjectInfo>
		<BarcodeObject>
			<Name>lblCodigoQR</Name>
			<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
			<BackColor Alpha="0" Red="255" Green="255" Blue="255" />
			<LinkedObjectName></LinkedObjectName>
			<Rotation>Rotation0</Rotation>
			<IsMirrored>False</IsMirrored>
			<IsVariable>False</IsVariable>
			<Text>PL1-1-1</Text>
			<Type>QRCode</Type>
			<Size>Medium</Size>
			<TextPosition>None</TextPosition>
			<TextFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />
			<CheckSumFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />
			<TextEmbedding>None</TextEmbedding>
			<ECLevel>0</ECLevel>
			<HorizontalAlignment>Center</HorizontalAlignment>
			<QuietZonesPadding Left="0" Top="0" Right="0" Bottom="0" />
		</BarcodeObject>
		<Bounds X="82" Y="634" Width="1301" Height="720" />
	</ObjectInfo>
</DieCutLabel>`;

        function DYMO_imprimirCodigoProducto(codigoProducto) {
            let label = dymo.label.framework.openLabelXml(dymo_labelXml);
            label.setObjectText("lblCodigoProducto", codigoProducto);
            label.setObjectText("lblCodigoQR", codigoProducto);

            let printerName = dymo_printers[0].name; // Usar la primera encontrada
            label.print(printerName);
        }

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
                                    <button type="button" class="btn {{ session('temaPreferido') == 'dark' ? 'btn-light' : 'btn-dark' }} btn-sm btn-imprimir-codigo" 
                                            data-codigo="${row.codigoProducto}" data-toggle="tooltip" title="Imprimir código">
                                        <i class="fa-duotone fa-solid fa-qrcode"></i>
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

        $(document).on('click', '.btn-imprimir-codigo', function() {
            const codigoProducto = $(this).data('codigo');
            if (dymo_printers.length === 0) {
                Swal.fire({
                    theme: 'auto',
                    title: "¡Atención!",
                    text: "No hay impresoras DYMO instaladas.",
                    icon: "error",
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            Swal.fire({
                theme: 'auto',
                title: `¡ATENCIÓN!`,
                html: `¿Estás seguro de imprimir el código <b class="text-primary">${codigoProducto}</b>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Sí, imprimir`,
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    DYMO_imprimirCodigoProducto(codigoProducto);
                }
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
                theme: 'auto',
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
