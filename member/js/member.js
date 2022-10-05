var datatable;

$(document).ready(function() {

    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
    
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

    // offerings datatable
    if ($('.data-table-offerings tbody tr').length > 1) {
        datatable = $('.data-table-offerings').DataTable({
            'responsive': {
                    'breakpoints': [
                        { name: 'desktop', width: Infinity },
                        { name: 'tablet',  width: 1024 },
                        { name: 'fablet',  width: 768 },
                        { name: 'phone',   width: 480 }
                    ],
                    'details': {
                                renderer: function() { return false }
                            }
                },
            'pageLength': 25,
            'order': [],
            'language': {'lengthMenu': 'Per page: <select class="form-control d-inline w-auto ml-1"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="10000">All</option></select>'},
            // Do not remove buttons line below, it is required
            'buttons': [],
            'columnDefs': [
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

    $('#menu-button').click(function () {
        if (!$('body').hasClass('menu-active')) {
            $('body').addClass('menu-active');
        } else {
            $('body').removeClass('menu-active');
        }
    });
});