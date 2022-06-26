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
                url: '/banner/get-data',
                type: "POST"
            },
            order: [
                [3, "ASC"]
            ],
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false,  sortable: false}, // 0
                { data: 'id', name: 'id', visible: false, searchable: false },
                { data: 'title', name: 'title'},
                { data: 'banner_type', name: 'banner_type', className: 'col-center'},
				{ data: 'Actions', responsivePriority: -1, name: 'actions', orderable: false, searchable: false, className: 'col-center' },
			],
			columnDefs: [
                {
                    targets: [ 2 ],
                    render: function ( data, type, row ) {
                        return `
                            <div class="kt-user-card-v2">
                                <div class="kt-user-card-v2__pic">
                                    <img src="${row['image']}" class="m-img-rounded kt-marginless" alt="photo">
                                </div>
                                <div class="kt-user-card-v2__details">
                                    <span class="kt-user-card-v2__name">${row['title']}</span>
                                </div>
                            </div>
                        `;  
                    }
                }, 
				{
					targets: -1,
					render: function(data, type, full, meta) {
                        var editUrl = full['editUrl'];
                        var formToken = $('meta[name="csrf-token"]').attr('content');
                        var eHtml = ``;
                        
                        if (full['canEdit']){
                            eHtml += `
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="${editUrl}" title="Edit">
                                    <i class="la la-edit"></i>
                                </a>`;
                        }

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