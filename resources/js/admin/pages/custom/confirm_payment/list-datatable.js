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
                url: '/confirm_payment/get-data',
                type: "POST"
            },
            order: [
                [1, "desc"]
            ],
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false,  sortable: false}, // 0
                { data: 'id', name: 'id', visible: false, searchable: false },
                { data: 'invoice_invoice_number', name: 'invoices.invoice_number'},
                { data: 'transfer_date', name: 'transfer_date'},
                { data: 'amount', name: 'amount',className: 'col-right'},
                { data: 'bank_name', name: 'banks.name'},
                { data: 'status_approved', name: 'status_approved', className: 'col-center'},
				{ data: 'Actions', responsivePriority: -1, name: 'actions', orderable: false, searchable: false, className: 'col-center' },
			],
			columnDefs: [
                
                {
					targets: -4, 
					render: function(data, type, full, meta) {
                        return number_format(full['amount']);  
					},
				},
                {
                    targets: [ -2 ],
                    render: function ( data, type, row ) {
                        var statusText = row['statusText'];
                        var statusClass = row['statusClass'];

                        return `
                            <span class="btn btn-bold btn-sm btn-font-sm ${statusClass}"> ${statusText} </span>
                        `;  
                        
                    }
                },  
				{
					targets: -1,
					render: function(data, type, full, meta) {
                        var eHtml = ``;  
                        
                        
                        eHtml += `
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="${full['viewUrl']}" title="View">
                                    <i class="la la-eye"></i>
                                </a>`;
                        
                        return eHtml;  
					},
				}
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