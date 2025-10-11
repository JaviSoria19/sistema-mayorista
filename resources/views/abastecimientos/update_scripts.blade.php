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

        $('.idEmpresa').select2({
            language: "es",
            dropdownCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
            selectionCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}"
        });

        $('.idMarca').select2({
            language: "es",
            dropdownCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
            selectionCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}"
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

        $(document).on('click', '.btn-imprimir-codigos-disponibles', function() {
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
                html: `¿Estás seguro de imprimir los códigos de <b class="text-success">todos los productos disponibles</b>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Sí, imprimir`,
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#productos tr.table-success').each(function() {
                        const codigoProducto = $(this).find('.codigoProducto').text().trim();
                        DYMO_imprimirCodigoProducto(codigoProducto);
                    });
                }
            });
        });
    });

    $(document).ready(function() {
        const tabla = $("#productos");

        tabla.on("input", ".costoBaseUSD, .traspasoPorcentaje, .transporteUSD", function() {
            let valor = $(this).text();

            // Validar que sea numérico
            if (isNaN(valor) || valor.trim() === "") {
                $(this).text("0");
            }
        });

        tabla.on("blur", ".nombreProducto", function() {
            let valor = $(this).text().trim();

            if (valor === "") {
                $(this).text("PRODUCTO SIN NOMBRE");
            }
        });

        // Aplicar hacia abajo cambios en celdas editables
        tabla.on("blur", ".costoBaseUSD, .traspasoPorcentaje, .transporteUSD, .nombreProducto", function() {
            const celda = $(this);
            const valor = celda.text().trim();
            const clase = celda.attr("class").split(" ").find(c => ["costoBaseUSD",
                "traspasoPorcentaje", "transporteUSD", "nombreProducto"
            ].includes(c));

            if (!clase) return;

            const filaActual = celda.closest("tr");
            const filas = filaActual.nextAll("tr");

            filas.each(function() {
                const fila = $(this);
                if (fila.hasClass("table-success")) { // Solo disponibles
                    const celdaDestino = fila.find("." + clase);
                    if (celdaDestino.is("[contenteditable=true]")) {
                        celdaDestino.text(valor);
                    }
                }
            });
        });

        // Aplicar hacia abajo cambios en los selects
        tabla.on("change", ".idEmpresa, .idMarca", function() {
            const select = $(this);
            const clase = select.hasClass("idEmpresa") ? "idEmpresa" : "idMarca";
            const valor = select.val();
            const texto = select.find("option:selected").text();

            const filaActual = select.closest("tr");
            const filas = filaActual.nextAll("tr");

            filas.each(function() {
                const fila = $(this);
                if (fila.hasClass("table-success")) { // Solo disponibles
                    const selectDestino = fila.find("." + clase);
                    if (selectDestino.length) {
                        selectDestino.val(valor);
                        // actualiza el texto visible si usas select2 o similar
                        selectDestino.val(valor).trigger('change.select2');
                    }
                }
            });
        });
    });
</script>
