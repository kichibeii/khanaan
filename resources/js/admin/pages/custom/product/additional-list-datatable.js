"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

	var initTable1 = function() {
        var table = $('#kt_table');

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
                url: '/product/'+$('#product_id').val()+'/additional-stock/get-data',
                type: "POST",
            },
            order: [
                [1, "DESC"]
            ],
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false,  sortable: false}, // 0
                { data: 'id', name: 'id', visible: false, searchable: false },
                { data: 'additional_date', name: 'additional_date'},
                { data: 'admin', name: 'admin'},
				{ data: 'Actions', responsivePriority: -1, name: 'actions', orderable: false, searchable: false, className: 'col-center' },
			],
			columnDefs: [
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