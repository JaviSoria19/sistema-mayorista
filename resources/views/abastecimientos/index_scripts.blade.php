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
            const btnGuardar = document.getElementById('btnGuardar');
            let productos = [];

            $("#productos tbody tr").each(function() {
                let fila = $(this);

                let idEmpresa = fila.find('.idEmpresa').text();
                let idMarca = fila.find('.idMarca').text();
                let nombreProducto = fila.find('.nombreProducto').text().trim().toUpperCase();
                let codigoProducto = fila.find('.codigoProducto').text();
                let costoBaseUSD = parseFloat(fila.find('.costoBaseUSD').text());
                let traspasoPorcentaje = parseFloat(fila.find('.traspasoPorcentaje').text());
                let transporteUSD = parseFloat(fila.find('.transporteUSD').text());

                productos.push({
                    idEmpresa: idEmpresa,
                    idMarca: idMarca,
                    nombreProducto: nombreProducto,
                    codigoProducto: codigoProducto,
                    costoBaseUSD: costoBaseUSD,
                    traspasoPorcentaje: traspasoPorcentaje,
                    transporteUSD: transporteUSD,
                });
            });

            /*
            console.log(productos);
            */

            if (productos.length === 0) {
                Swal.fire({
                    theme: 'auto',
                    title: "¡No válido!",
                    text: "Debe agregar al menos un producto a la tabla.",
                    icon: "warning"
                });
                return;
            }

            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<i class="fa-duotone fa-solid fa-loader fa-spin"></i> Guardando...';

            $.ajax({
                url: "{{ route('abastecimientos.create') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    productos: productos
                },
                success: function(response) {
                    if (response.success) {
                        btnGuardar.disabled = false;
                        btnGuardar.innerHTML = '<i class="fa-solid fa-duotone fa-save"></i> Guardar';
                        
                        Swal.fire('Éxito', response.message, 'success');
                        $('#modalCreate').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();

                        $("#productos tbody").empty();
                        actualizarTotales();
                    } else {
                        btnGuardar.disabled = false;
                        btnGuardar.innerHTML = '<i class="fa-solid fa-duotone fa-save"></i> Guardar';

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
                theme: 'auto',
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
                        theme: 'auto',
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
            let idEmpresa = $("#empresa option:selected").val();
            let marca = $("#marca option:selected").text().trim();
            let idMarca = $("#marca option:selected").val();
            let nombreProducto = $("#nombreProducto").val().trim();
            let costoBase = parseFloat($("#costoBaseUSD").val());
            let traspasoPorcentaje = parseFloat($("#traspasoPorcentaje").val());
            let transporteUSD = parseFloat($("#transporteUSD").val());
            let cantidad = parseInt($("#cantidad").val());

            if (!empresa || !marca || !nombreProducto || isNaN(costoBase) || isNaN(traspasoPorcentaje) || isNaN(
                    transporteUSD) || isNaN(cantidad)) {
                Swal.fire({
                    theme: 'auto',
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
                /*let fechaHora = ahora.getFullYear() +
                    String(ahora.getMonth() + 1).padStart(2, "0") +
                    String(ahora.getDate()).padStart(2, "0") + "-" +
                    String(ahora.getHours()).padStart(2, "0") +
                    String(ahora.getMinutes()).padStart(2, "0");*/
                let fechaHora = ahora.getFullYear() +
                    String(ahora.getMonth() + 1).padStart(2, "0") +
                    String(ahora.getDate()).padStart(2, "0");

                /*let codigo = empresa.substring(0, 2).toUpperCase() + "-" +
                    marca.substring(0, 2).toUpperCase() + "-" +
                    fechaHora + "-" +
                    String(numeroFila).padStart(2, "0");*/
                let codigo = fechaHora + "-" + String(numeroFila).padStart(2, "0");

                let costoTraspasoUSD = (costoBase * traspasoPorcentaje / 100).toFixed(2);
                let costoFinalUSD = (costoBase + parseFloat(costoTraspasoUSD) + transporteUSD).toFixed(2);

                let fila = `
                        <tr>
                            <td class="numero">${numeroFila}</td>
                            <td class="visually-hidden idEmpresa">${idEmpresa}</td>
                            <td class="visually-hidden idMarca">${idMarca}</td>
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
                totalCostoBase += parseFloat($(this).find("td:eq(5)").text());
                totalCostoFinal += parseFloat($(this).find("td:eq(9)").text());
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
