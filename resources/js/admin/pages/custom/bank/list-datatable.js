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
                url: '/bank/get-data',
                type: "POST"
            },
            order: [
                [1, "DESC"]
            ],
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex' }, // 0
                { data: 'id', name: 'id' },
                { data: 'image', name: 'image'},
                { data: 'account_number', name: 'account_number'},
                { data: 'owner_name', name: 'owner_name'},
                { data: 'branch', name: 'branch'},
                { data: 'status', name: 'status' },
				{ data: 'Actions', responsivePriority: -1 },
			],
			columnDefs: [
                {
                    targets: [ 0 ],
                    searchable: false,
                    sortable: false,
                },
                
                {
                    targets: [ 1 ],
                    visible: false,
                    searchable: false
                }, 

                {
                    targets: [ 2 ],
                    render: function ( data, type, row ) {
                        return `
                            <div class="kt-user-card-v2">
                                <div class="kt-user-card-v2__pic">
                                    <img src="${row['image']}" class="m-img-rounded kt-marginless" alt="photo">
                                </div>
                                <div class="kt-user-card-v2__details">
                                    <span class="kt-user-card-v2__name">${row['name']}</span>
                                </div>
                            </div>
                        `;  
                    }
                },  
                {
                    targets: [ -2 ],
                    className: 'col-center',
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
                    className: 'col-center',
                    orderable: false,
					render: function(data, type, full, meta) {
                        var editUrl = full['editUrl'];
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