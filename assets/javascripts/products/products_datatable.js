$(function () {
    $("#admin_products_table").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#admin_products_table_wrapper .col-md-6:eq(0)');
})

