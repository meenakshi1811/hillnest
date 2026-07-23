window.AdminDT = {
    defaults: {
        processing: true,
        serverSide: true,
        pageLength: 15,
        lengthMenu: [10, 15, 25, 50],
        order: [],
        autoWidth: false,
        responsive: true,
        language: {
            search: '',
            searchPlaceholder: 'Search...',
            lengthMenu: 'Show _MENU_',
            info: 'Showing _START_–_END_ of _TOTAL_',
            infoEmpty: 'No records',
            infoFiltered: '(filtered from _MAX_)',
            zeroRecords: 'No matching records found',
            processing: 'Loading...',
            paginate: {
                previous: 'Prev',
                next: 'Next'
            }
        },
        dom: '<"admin-dt-top"lf>rt<"admin-dt-bottom"ip>',
        drawCallback: function () {
            var api = this.api();
            var pages = api.page.info().pages;
            var wrap = jQuery(api.table().container());

            wrap.find('.dataTables_paginate').toggle(pages > 1);
            wrap.find('.admin-dt-bottom').toggleClass('is-single-page', pages <= 1);
        }
    },

    init: function (selector, options) {
        var config = Object.assign({}, this.defaults, options || {});

        if (config.ajax && typeof config.ajax === 'string') {
            config.ajax = { url: config.ajax };
        }

        return jQuery(selector).DataTable(config);
    }
};
