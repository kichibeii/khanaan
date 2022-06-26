"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

	var initTable1 = function() {
        var table = $('#kt_table');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
		});

        $('.kt_daterangepicker').daterangepicker({
            //autoUpdateInput: false,
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            locale: {
                format: "DD/MM/YYYY"
            }
            
        });
        $('.kt_daterangepicker').val("");

        

        $('body').on('click', '[data-action="delete-record"]', function(event) {
            event.preventDefault();
            if (confirm('Apakah anda serius akan menghapus data ini?')) {
                $(this).parents('form').submit();
            }
        });

		// begin first table
		var t = table.DataTable({
            pageLength: 10,
			responsive: true,
			searchDelay: 500,
			processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/csv_log/get-data',
                type: "POST",
                data: function(d){
                    d.ship_id = $('#ship_id').val();
                    //d.period = $('#period').val();
                },
            },
            order: [
                [1, "desc"]
            ],
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex' }, // 0
                { data: 'id', name: 'id' },
                { data: 'ship_name', name: 'ship_name'},
                { data: 'trx_date', name: 'trx_date'},
                { data: 'import_date', name: 'import_date'},
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
					targets: -1,
                    className: 'col-center',
					orderable: false,
					render: function(data, type, full, meta) {
                        var viewUrl = full['viewUrl'];
                        var eHtml = ``;
                        
                        eHtml += `
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="${viewUrl}" title="View">
                                    <i class="la la-eye"></i>
                                </a>`;

                        return eHtml;  
					},
				}
			],
        });
        
        $('#ship_id').select2({
            placeholder: 'Semua Kapal',
            allowClear: true,
            width: '100%',
            
            language: {
                searching: function() {
                    return "Searching...";
                }
            },
			ajax: {
				url: '/ship/get-data-array',
				dataType: 'json',
				type: 'POST',
				delay: 250,
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache: true
			}
        });
        
        $('#ship_id').on("select2:clear", function(e) {
            $("#ship_id").val('').trigger('change');
            t.ajax.reload();
        });

        $('#ship_id').on("select2:select", function(e) {
            t.ajax.reload();
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