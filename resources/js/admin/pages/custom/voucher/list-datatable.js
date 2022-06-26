"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

	var initTable1 = function() {
        var table = $('#kt_table');

        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '')
              .replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
              prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
              sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
              dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
              s = '',
              toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + (Math.round(n * k) / k)
                  .toFixed(prec);
              };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
              .split('.');
            if (s[0].length > 3) {
              s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '')
              .length < prec) {
              s[1] = s[1] || '';
              s[1] += new Array(prec - s[1].length + 1)
                .join('0');
            }
            return s.join(dec);
        }

        $('body').on('click', '[data-action="delete-record"]', function(event) {
            event.preventDefault();
            if (confirm('Apakah anda serius akan menghapus data ini?')) {
                $(this).parents('form').submit();
            }
        });

		// begin first table
		table.DataTable({
            pageLength: 10,
			responsive: true,
			searchDelay: 500,
			processing: true,
            serverSide: true,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/voucher/get-data',
                type: "POST"
            },
            order: [
                [1, "DESC"]
            ],
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false,  sortable: false}, // 0
                { data: 'id', name: 'id', visible: false, searchable: false },
                { data: 'code', name: 'code'},
                { data: 'nominal', name: 'nominal', className:'col-right'},
                { data: 'start_date', name: 'start_date'},
                { data: 'end_date', name: 'end_date'},
                { data: 'user_name', name: 'users.name'},
                { data: 'activated_date', name: 'activated_date'}
			],
			columnDefs: [
                
                {
					targets: 3, 
					render: function(data, type, full, meta) {
                        return number_format(full['nominal']);  
					},
				},
			],
		});
	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
		},

	};

}();

jQuery(document).ready(function() {
	KTDatatablesDataSourceAjaxServer.init();
});