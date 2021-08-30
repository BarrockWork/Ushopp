$(function () {
    // Products datatable
    if($("#admin_products_table") !== undefined) {
        $("#admin_products_table").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#admin_products_table_wrapper .col-md-6:eq(0)');
    }

    // Comments datatable
    if($("#admin_comments_table") !== undefined) {
        $("#admin_comments_table").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#admin_products_table_wrapper .col-md-6:eq(0)');
    }
})

