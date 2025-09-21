$(document).ready(function () {
    // Inicializar Select2 con opciones personalizadas
    $('#select2').select2({
        language: "es",
        dropdownCssClass: 'bg-dark',
        selectionCssClass: 'bg-dark mb-3'
    });
});


//Script para deshabilitar el botón de envío del formulario de creación
if (document.getElementById('createForm')) {
    document.getElementById('createForm').onsubmit = function () {
        document.getElementById('createSubmitButton').disabled = true;
    };
}
