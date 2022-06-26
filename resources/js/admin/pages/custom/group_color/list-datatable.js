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
                url: '/group_color/get-data',
                type: "POST"
            },
            order: [
                [4, "ASC"]
            ],
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false,  sortable: false}, // 0
                { data: 'id', name: 'id', visible: false, searchable: false },
                { data: 'color_hex', name: 'color_hex', className: 'col-center', orderable: false},
                { data: 'title', name: 'title'},
                { data: 'sort_order', name: 'sort_order', className: 'col-center'},
                { data: 'status', name: 'status', className: 'col-center'},
				{ data: 'Actions', responsivePriority: -1, name: 'actions', orderable: false, searchable: false, className: 'col-center' },
			],
			columnDefs: [
                {
                    targets: [ 2 ],
                    render: function ( data, type, row ) {
                        return `
                            <div class="btn btn-brand btn-elevate btn-circle btn-icon" style="background-color:#${row['color_hex']}; border-color:#${row['color_hex']};">&nbsp;</div>
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