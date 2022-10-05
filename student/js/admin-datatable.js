var datatable;

$(document).ready(function() {

    // Date renderer for DataTables from cdn.datatables.net/plug-ins/1.10.21/dataRender/datetime.js
    $.fn.dataTable.render.moment = function ( from, to, locale ) {
        if ( arguments.length === 1 ) {
            locale = 'en';
            to = from;
            from = 'YYYY-MM-DD';
        } else if ( arguments.length === 2 ) {
            locale = 'en';
        }
        return function ( d, type, row ) {
            if (! d) {
                return type === 'sort' || type === 'type' ? 0 : d;
            }
            var m = window.moment( d, from, locale, true );
            return m.format( type === 'sort' || type === 'type' ? 'x' : to );
        };
    };

    // courses datatable
    if ($('.data-table-courses tbody tr').length > 1) {
        datatable = $('.data-table-courses').DataTable({
            'pageLength': 50,
            'order': [[0, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': -1, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
            ],
        });
    }
    
    // course-categories datatable
    if ($('.data-table-course-categories tbody tr').length > 1) {
        datatable = $('.data-table-course-categories').DataTable({
            'pageLength': 50,
            'order': [[0, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': 2, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
            ],
        });
    }
    
    // students datatable
    if ($('.data-table-students tbody tr').length > 1) {
        datatable = $('.data-table-students').DataTable({
            'pageLength': 50,
            'order': [[1, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': 3, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
                { 'targets': 4, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
                { 'targets': -1, 'width': '150px' },
            ],
        });
    }
    
    // instructors datatable
    if ($('.data-table-instructors tbody tr').length > 1) {
        datatable = $('.data-table-instructors').DataTable({
            'pageLength': 50,
            'order': [[1, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': 2, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
                { 'targets': 3, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
            ],
        });
    }
    
    // report-courses datatable
    if ($('.data-table-report-courses tbody tr').length > 1) {
        datatable = $('.data-table-report-courses').DataTable({
            'pageLength': -1,
            'order': [[1, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': -1, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
            ],
        });
    }

    // report-students datatable
    if ($('.data-table-report-students tbody tr').length > 1) {
        datatable = $('.data-table-report-students').DataTable({
            'pageLength': -1,
            'order': [[1, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': -1, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
                { 'targets': -2, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
            ],
        });
    }

    // report-instructors datatable
    if ($('.data-table-report-instructors tbody tr').length > 1) {
        datatable = $('.data-table-report-instructors').DataTable({
            'pageLength': -1,
            'order': [[1, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': -1, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
                { 'targets': -2, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
            ],
        });
    }

    // report-logins datatable
    if ($('.data-table-report-logins tbody tr').length > 1) {
        datatable = $('.data-table-report-logins').DataTable({
            'pageLength': -1,
            'order': [[1, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': -1, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
            ],
        });
    }

    // contact datatable
    if ($('.data-table-contact tbody tr').length > 1) {
        datatable = $('.data-table-contact').DataTable({
            'pageLength': 50,
            'order': [[1, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': 0, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
            ],
        });
    }

    // Rest of generic setup - search bar location, export csv button, pagination location
    if (datatable) {
        var tableTools = new $.fn.dataTable.Buttons( datatable, {
            "buttons": [{
                'extend': 'csv',
                'text': 'Export CSV <i class="fe fe-download"></i>',
                'className': 'btn btn-secondary btn-sm mr-3',
                'exportOptions': {
                    'orthogonal': null
                }
            }]
        } );
        $( tableTools.dom.container ).insertAfter('.card-title');
        $('.data-table-search').keyup(function() {
            datatable.search($(this).val()).draw();
        })
        $('.data-table-records-info').append($('.dataTables_info'));
        $('.data-table-pagination').append($('.dataTables_paginate'));
        $('.data-table-show-per-page').append($('.dataTables_length'));
    }
});