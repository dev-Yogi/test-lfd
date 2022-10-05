var datatable;
var columns;

$(document).ready(function() {

    // Does this page have a standard applicaton table?
    if ($('.data-table-applications tbody tr').length > 1) {
        datatable = $('.data-table-applications').DataTable({
            'autoWidth': false,
            'pageLength': 25,
            'order': [
                [2, 'desc']
            ],
            'columns': [
                { 'width': '10px' },
                { 'width': '300px' },
                { 'width': '123px' },
                { 'width': '240px' },
                { 'width': '140px' },
            ],
            'columnDefs': [
                { 'targets': 0, 'orderable': false },
                { 'targets': -1, 'searchable': false }
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

    // Does this page have a applications-for-a-job table?
    if ($('.data-table-applications-job').length > 0) {
        if (!$('.th-pq').length) {
            columns = [
                { 'width': '40px' },
                { 'width': '300px' },
                { 'width': '123px' },
                { 'width': '240px' },
                { 'width': '140px' },
                { 'width': '140px' },
            ];
        } else {
            columns = [
                { 'width': '40px' },
                { 'width': '40px' },
                { 'width': '300px' },
                { 'width': '123px' },
                { 'width': '240px' },
                { 'width': '140px' },
                { 'width': '140px' },
            ];
        }
        datatable = $($('.data-table-applications-job')[0]).DataTable({
            'autoWidth': false,
            'pageLength': 50,
            'order': [
                [2, 'desc']
            ],
            'columnDefs': [
                { 'targets': 0, 'orderable': false }
            ],
            'columns': columns,
            'language': {
                'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>',
                'zeroRecords': '<div class="text-muted p-5">No applications</div>',
                'info': 'Showing _START_ to _END_ of _TOTAL_ applications'
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

function openApplicationDatePicker() {
    $('.application-date-picker').slideDown('fast');
}

function closeApplicationDatePicker() {
    $('.application-date-picker').slideUp('fast');
}

function redirectApplicationByStatus() {
    var status = $('#selected-status').val();
    var url = '/member/applications/status/' + emp_id + '/' + status;
    if (status) {
        window.location.href = url;
    }
}

// On the job apps page, switching tabs
function switchWorkflowTab(panel_id) {
    // If the clicked tab is already active, do nothing
    var already_active = $('#applications-' + panel_id + ':visible').length > 0;
    if(!already_active) {
        $('.workflow .active').removeClass('active');
        $('#tab-' + panel_id).addClass('active');
        $('.data-table-applications-job .application-checkbox').prop('checked', false);

        $('.data-table-applications-job').hide();
        $('#applications-' + panel_id).show();

        // Reset the data table
        datatable.destroy();
        datatable = $('#applications-' + panel_id).DataTable({
            'autoWidth': false,
            'order': [
                [2, 'desc']
            ],
            'pageLength': 50,
            'columns': columns,
            'columnDefs': [
                { 'targets': 0, 'orderable': false }
            ],
            'language': {
                'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>',
                'zeroRecords': '<div class="text-muted p-5">No applications</div>',
                'info': 'Showing _START_ to _END_ of _TOTAL_ applications'
            }
        });
        $('.data-table-records-info').empty().append($('.dataTables_info'));
        $('.data-table-pagination').empty().append($('.dataTables_paginate'));
        $('.data-table-show-per-page').empty().append($('.dataTables_length'));
    }
}

$('.data-table-applications-job a').click(function(event) {
    event.stopPropagation();
})

$('.workflow-applications .custom-control-label').click(function(event) {
    var id = $(this).attr('for');
    var table = $(this).closest('table').attr('id');
    $('#' + table + ' #' + id).trigger('click');
    event.preventDefault();
    event.stopPropagation();
})

// Check that checkbox if they click on that row
$('.data-table-applications-job td').click(function(event) {
    // Don't check it if it's a link or the checkbox itself though
    if(event.target.tagName != 'LABEL' && event.target.tagName != 'A' && event.target.type != 'checkbox') {
        $(this).parent().find('.application-checkbox').trigger('click');
    }
})

// Check that checkbox if they click on that row
$('.table-applicants td').click(function(event) {
    // Don't check it if it's a link or the checkbox itself though
    if(event.target.tagName != 'LABEL' && event.target.tagName != 'A' && event.target.type != 'checkbox') {
        $(this).parent().find('.application-checkbox').trigger('click');
    }
})