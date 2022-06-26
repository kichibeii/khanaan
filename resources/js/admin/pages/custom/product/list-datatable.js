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
                url: '/product/get-data',
                type: "POST"
            },
            order: [
                [1, "DESC"]
            ],
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false,  sortable: false}, // 0
                { data: 'id', name: 'id', visible: false, searchable: false },
                { data: 'code', name: 'code', visible: false}, 
                { data: 'title', name: 'title'},
                { data: 'price', name: 'price', className: 'col-right'},
                { data: 'qty', name: 'qty', className: 'col-center'},
                { data: 'published_on', name: 'published_on'},
                { data: 'brand_name', name: 'brands.name'},
                { data: 'categories', name: 'categories'},
                { data: 'collections', name: 'collections'},
                { data: 'status', name: 'status', className: 'col-center'},
				{ data: 'Actions', responsivePriority: -1, name: 'actions', orderable: false, searchable: false, className: 'col-center' },
			],
			columnDefs: [
                {
                    targets: [ 3 ],
                    render: function ( data, type, row ) {
                        return `
                            <div class="kt-user-card-v2">
                                <div class="kt-user-card-v2__pic">
                                    <img src="${row['image']}" class="m-img-rounded kt-marginless" alt="photo">
                                </div>
                                <div class="kt-user-card-v2__details">
                                    <span class="kt-user-card-v2__name">${row['title']}</span>
                                    <span class="kt-user-card-v2__email kt-link">#${row['code']}</span>
                                </div>
                            </div>
                        `;  
                    }
                },  
                {
					targets: 4, 
					render: function(data, type, full, meta) {
                        var eHtml = ``;
                        if (full['discount'] > 0){
                            eHtml += `<strike style="color:red;">${number_format(full['price'])}</strike> ${number_format(full['discount'])}`;
                        } else {
                            eHtml += `${number_format(full['price'])}`;
                        }
                        return eHtml;  
					},
				},
                {
					targets: 5, 
					render: function(data, type, full, meta) {
                        return number_format(full['qty']);  
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
                        var editUrl = full['editUrl'];
                        var deleteUrl = full['deleteUrl'];
                        var formToken = $('meta[name="csrf-token"]').attr('content');
                        var eHtml = ``;

                        eHtml += ` 
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="${full['viewUrl']}">View</a>`;

                        if (full['canEdit']){
                            eHtml += `<a class="dropdown-item" href="${editUrl}">Edit</a>`;
                        }

                        eHtml += `<a class="dropdown-item" href="${full['viewStock']}">Lihat Stok</a>`;
                        eHtml += `<a class="dropdown-item" href="${full['additionalStock']}">Penambahan Stok</a>`; 
                        eHtml += `<a class="dropdown-item" href="${full['imagesUrl']}">Kelola Foto</a>`; 
                                    
                        eHtml += `
                                </div>
                            </div>
                        `;

                                
                        

                        

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