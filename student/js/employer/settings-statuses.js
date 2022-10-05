$(document).ready(function () {
    if ($('.data-table-settings tbody tr').length > 1) {
        var datatable = $('.data-table-settings').DataTable({
            'autoWidth': false,
            'pageLength': 15,
            'order': [
                
            ],
            'columnDefs': [
                { 'targets': -1, 'orderable': false, 'searchable': false }
            ],
            'language': {
                'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>'
            }
        });
        $('.data-table-search').keyup(function () {
            datatable.search($(this).val()).draw();
        })
        $('.data-table-records-info').append($('.dataTables_info'));
        $('.data-table-pagination').append($('.dataTables_paginate'));
        $('.data-table-show-per-page').append($('.dataTables_length'));
    }
});