$(document).ready(function() {
    if ($('.data-table-jobs tbody tr').length > 1) {
        var datatable = $('.data-table-jobs').DataTable({
            'autoWidth': false,
            'order': [
                [0, 'desc']
            ],
            'columns': [
                { 'width': '40px' },
                null,
                { 'width': '75px' },
                { 'width': '75px' },
                { 'width': '10px' },
                { 'width': '10px' },
            ],
            'columnDefs': [
                { 'targets': -1, 'orderable': false, 'searchable': false }
            ],
            'language': {
                'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>'
            }
        });
        $('.data-table-search').keyup(function() {
            datatable.search($(this).val()).draw();
        })
        $('.data-table-records-info').append($('.dataTables_info'));
        $('.data-table-pagination').append($('.dataTables_paginate'));
        $('.data-table-show-per-page').append($('.dataTables_length'));
        $('#DataTables_Table_0_filter').hide();
    }
});

function closeJob(job_id) {
    var message = 'Are you sure you want to close the posting?';
    if(confirm(message)) {
        $.get(base_url + 'employer/job/close/' + job_id, function (response) {
            console.log(response);
            location.reload();
        });
    }
}