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
            searching: false,
            bLengthChange : false,
            pageLength: 10,
			responsive: true,
			searchDelay: 500,
			processing: true,
            serverSide: true,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ship/get-data',
                type: "POST",
                data: function(d){
                    d.dashboard = 1;
                },
            },
            order: [
                [1, "desc"]
            ],
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex' }, // 0
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name'},
                { data: 'code', name: 'code'},
                { data: 'client', name: 'client'},
                { data: 'last_online', name: 'last_online'},
                { data: 'last_data', name: 'last_data'},
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
					targets: -1,
                    className: 'col-center',
					orderable: false,
					render: function(data, type, full, meta) {
                        
                        var eHtml = '';
                        if (full['canReport']){
                            eHtml += `
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="${full['reportUrl']}" title="Report">
                                    <i class="la la-file-text"></i>
                                </a>`;
                        }

                        if (full['canTrend']){
                            eHtml += `
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="${full['trendUrl']}" title="Trend">
                                    <i class="la la-line-chart"></i>
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