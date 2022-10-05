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

    // members datatable
    if ($('.data-table-members tbody tr').length > 1) {
        datatable = $('.data-table-members').DataTable({
            'pageLength': 50,
            'order': [[0, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': 4, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
                { 'targets': 5, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY hh:mma' ) },
                { 'targets': -1, 'searchable': false, 'orderable': false }
            ],
        });
    }

    // offerings datatable
    if ($('.data-table-offerings tbody tr').length > 1) {
        datatable = $('.data-table-offerings').DataTable({
            'pageLength': 50,
            'order': [[0, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': -2, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD', 'D MMMM, YYYY' ) },
            ],
        });
    }

    // offering-categories datatable
    if ($('.data-table-offering-categories tbody tr').length > 1) {
        datatable = $('.data-table-offering-categories').DataTable({
            'pageLength': 50,
            'order': [[0, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': 1, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
                { 'targets': -1, 'searchable': false, 'orderable': false }
            ],
        });
    }

    // offering-queue datatable
    if ($('.data-table-offering-queue tbody tr').length > 1) {
        datatable = $('.data-table-offering-queue').DataTable({
            'pageLength': 50,
            'order': [[0, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': 2, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
                { 'targets': -1, 'searchable': false, 'orderable': false }
            ],
        });
    }

    // offering-queue datatable
    if ($('.data-table-offerings-all-columns tbody tr').length > 1) {
        datatable = $('.data-table-offerings-all-columns').DataTable({
            'pageLength': 10,
            'order': [[0, 'asc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
            ],
        });
    }

    // offering-queue datatable
    if ($('.data-table-eoc tbody tr').length > 1) {
        datatable = $('.data-table-eoc').DataTable({
            'pageLength': 10,
            'order': [[0, 'desc']],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': -1, 'searchable': false, 'orderable': false }
            ],
        });
    }

    // offering-queue datatable
    if ($('.data-table-netsuite-contacts tbody tr').length > 1) {
        datatable = $('.data-table-netsuite-contacts').DataTable({
            'pageLength': 50,
            'order': [],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
            ],
        });
    }

    // offering-queue datatable
    if ($('.data-table-offering-zipcodes tbody tr').length > 1) {
        datatable = $('.data-table-offering-zipcodes').DataTable({
            'pageLength': 50,
            'order': [],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
                { 'targets': 1, 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss', 'D MMMM, YYYY' ) },
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