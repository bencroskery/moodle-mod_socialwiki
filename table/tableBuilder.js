$(document).ready(function () {
    var table = $('.datatable').DataTable({
        "scrollY": true,
        "scrollX": true,
        "info": false,
        "paging": false
    });
    if (table.page.len() < 12) {
        $(this).find(".dataTables_filter").hide();
    }
});
