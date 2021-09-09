$(function () {
    // Products datatable
    if($("#admin_products_table") !== undefined) {
        $("#admin_products_table").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#admin_products_table_wrapper .col-md-6:eq(0)');
        $("#admin_products_table2").DataTable({
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

    // Users datatable
    if($("#admin_users_table") !== undefined) {
        $("#admin_users_table").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#admin_products_table_wrapper .col-md-6:eq(0)');
    }

    // Orders datatable
    if($("#admin_orders_table") !== undefined) {
        $("#admin_orders_table").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#admin_products_table_wrapper .col-md-6:eq(0)');
        $("#admin_orders_table2").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#admin_products_table_wrapper .col-md-6:eq(0)');

    }
})

