"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

	var initTable1 = function() {
        var table = $('#kt_table');


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
                url: '/order/get-data',
                type: "POST"
            },
            order: [
                [1, "desc"]
            ],
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false,  sortable: false}, // 0
                { data: 'id', name: 'id', visible: false, searchable: false },
                { data: 'invoice_date', name: 'invoice_date'},
                { data: 'invoice_number', name: 'invoice_number'},
                { data: 'name', name: 'users.name'},
                { data: 'grand_total', name: 'grand_total',className: 'col-right'},
                { data: 'status_payment', name: 'status_payment', className: 'col-center'},
                { data: 'status_invoice', name: 'status_invoice', className: 'col-center'},
				{ data: 'Actions', responsivePriority: -1, name: 'actions', orderable: false, searchable: false, className: 'col-center' },
			],
			columnDefs: [
                
                {
                    targets: [ -3 ],
                    render: function ( data, type, row ) {
                        var statusText = row['statusTextPayment'];
                        var statusClass = row['statusClassPayment'];

                        return `
                            <span class="btn btn-bold btn-sm btn-font-sm ${statusClass}"> ${statusText} </span>
                        `;  
                        
                    }
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