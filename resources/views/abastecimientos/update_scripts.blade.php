<script>
    $(document).ready(function() {
        $('.empresa').select2({
            language: "es",
            dropdownCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
            selectionCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}"
        });

        $('.marca').select2({
            language: "es",
            dropdownCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}",
            selectionCssClass: "{{ session('temaPreferido') == 'dark' ? 'bg-dark' : '' }}"
        });
    });
</script>