"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

	var initTable1 = function() {
        var table = $('#kt_table');

		// begin first table
		var oTable = table.DataTable({
            //scrollY: '50vh',
			scrollX: true,
            scrollCollapse: true,
            paging: false,
            ordering: false,
            info: false,
            searching: false,
            pageLength: 10,
			processing: true,
            serverSide: true,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/csv_log/get-data-detail',
                data: function(d){
                    d.csv_log_id = $('#csv_log_id').val();
                },
                type: "POST"
            },
            order: [
                [1, "desc"]
            ],
			columns: [
				{ data: 'Actions', responsivePriority: -1 },
                { data: 'id', name: 'id' },
                { data: 'trx_time', name: 'trx_time'},
                { data: 'ME_PS_VOL', name: 'ME_PS_VOL'},
                { data: 'ME_STBD_VOL', name: 'ME_STBD_VOL'},
                { data: 'AE_VOL', name: 'AE_VOL'},
                { data: 'BOIL_VOL', name: 'BOIL_VOL'},
                { data: 'EXT_VOL', name: 'EXT_VOL'},
                { data: 'DT01', name: 'DT01'},
                { data: 'DT02', name: 'DT02'},
                { data: 'DT03', name: 'DT03'},
                { data: 'DT04', name: 'DT04'},
                { data: 'DT05', name: 'DT05'},
                { data: 'DT06', name: 'DT06'},
                { data: 'DT07', name: 'DT07'},
                { data: 'DT08', name: 'DT08'},
                { data: 'DT09', name: 'DT09'},
                { data: 'DT10', name: 'DT10'},
                { data: 'DT11', name: 'DT11'},
                { data: 'DT12', name: 'DT12'},
                { data: 'DT13', name: 'DT13'},
                { data: 'DT14', name: 'DT14'},
                { data: 'DT15', name: 'DT15'}
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
                    targets: [ 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22 ],
                    className: 'col-right',
                },
                {
					targets: 0,
                    className: 'col-center',
					orderable: false,
					render: function(data, type, full, meta) {
                        var eHtml = ``;
                        
                        if (full['canEdit']){
                            eHtml += `
                                <a class="edit btn btn-sm btn-clean btn-icon btn-icon-md" href="" title="Edit">
                                    <i class="la la-edit"></i>
                                </a>`;
                        }

                        return eHtml;  
					},
				}



                
			],
        });

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            oTable.fnUpdate('<div style="width:80px"><a class="edit btn btn-sm btn-clean btn-icon btn-icon-md" href="" title="Edit" href=""><i class="la la-edit"></i></a></div>', nRow, 0, false);
            var aData = oTable.fnGetData(nRow);
            console.log(aData);

            $.ajax({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/csv_log/update-data',
                data: {
                    id: aData.id,
                    ME_PS_VOL: jqInputs[0].value,
                    ME_STBD_VOL: jqInputs[1].value,
                    AE_VOL: jqInputs[2].value,
                    BOIL_VOL: jqInputs[3].value,
                    EXT_VOL: jqInputs[4].value,
                    DT01: jqInputs[5].value,
                    DT02: jqInputs[6].value,
                    DT03: jqInputs[7].value,
                    DT04: jqInputs[8].value,
                    DT05: jqInputs[9].value,
                    DT06: jqInputs[10].value,
                    DT07: jqInputs[11].value,
                    DT08: jqInputs[12].value,
                    DT09: jqInputs[13].value,
                    DT10: jqInputs[14].value,
                    DT11: jqInputs[15].value,
                    DT12: jqInputs[16].value,
                    DT13: jqInputs[17].value,
                    DT14: jqInputs[18].value,
                    DT15: jqInputs[19].value,
                }
            }).then(function (data) {
                
            });

            oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
            oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
            oTable.fnUpdate(jqInputs[4].value, nRow, 4, false);
            oTable.fnUpdate(jqInputs[5].value, nRow, 5, false);
            
            oTable.fnDraw();


        }

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            jqTds[2].innerHTML = '<div style="width:100px">'+aData['ME_PS_VOL']+'</div>';
            jqTds[3].innerHTML = '<div style="width:100px">'+aData['ME_STBD_VOL']+'</div>';
            jqTds[4].innerHTML = '<div style="width:100px">'+aData['AE_VOL']+'</div>';
            jqTds[5].innerHTML = '<div style="width:100px">'+aData['BOIL_VOL']+'</div>';
            jqTds[6].innerHTML = '<div style="width:100px">'+aData['EXT_VOL']+'</div>';
            jqTds[7].innerHTML = '<div style="width:100px">'+aData['DT01']+'</div>';
            jqTds[8].innerHTML = '<div style="width:100px">'+aData['DT02']+'</div>';
            jqTds[9].innerHTML = '<div style="width:100px">'+aData['DT03']+'</div>';
            jqTds[10].innerHTML = '<div style="width:100px">'+aData['DT04']+'</div>';
            jqTds[11].innerHTML = '<div style="width:100px">'+aData['DT05']+'</div>';
            jqTds[12].innerHTML = '<div style="width:100px">'+aData['DT06']+'</div>';
            jqTds[13].innerHTML = '<div style="width:100px">'+aData['DT07']+'</div>';
            jqTds[14].innerHTML = '<div style="width:100px">'+aData['DT08']+'</div>';
            jqTds[15].innerHTML = '<div style="width:100px">'+aData['DT09']+'</div>';
            jqTds[16].innerHTML = '<div style="width:100px">'+aData['DT10']+'</div>';
            jqTds[17].innerHTML = '<div style="width:100px">'+aData['DT11']+'</div>';
            jqTds[18].innerHTML = '<div style="width:100px">'+aData['DT12']+'</div>';
            jqTds[19].innerHTML = '<div style="width:100px">'+aData['DT13']+'</div>';
            jqTds[20].innerHTML = '<div style="width:100px">'+aData['DT14']+'</div>';
            jqTds[21].innerHTML = '<div style="width:100px">'+aData['DT15']+'</div>';
            jqTds[0].innerHTML = '<div style="width:80px"><a class="edit btn btn-sm btn-clean btn-icon btn-icon-md" href="" title="Edit" href=""><i class="la la-edit"></i></a></div>';
        }

        function editRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            
            jqTds[1].innerHTML = '<div style="width:80px">'+ aData['trx_time'] + '</div>';
            jqTds[2].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['ME_PS_VOL'] + '">';
            jqTds[3].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['ME_STBD_VOL'] + '">';
            jqTds[4].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['AE_VOL'] + '">';
            jqTds[5].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['BOIL_VOL'] + '">';
            jqTds[6].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['EXT_VOL'] + '">';
            jqTds[7].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT01'] + '">';
            jqTds[8].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT02'] + '">';
            jqTds[9].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT03'] + '">';
            jqTds[10].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT04'] + '">';
            jqTds[11].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT05'] + '">';
            jqTds[12].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT06'] + '">';
            jqTds[13].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT07'] + '">';
            jqTds[14].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT08'] + '">';
            jqTds[15].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT09'] + '">';
            jqTds[16].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT10'] + '">';
            jqTds[17].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT11'] + '">';
            jqTds[18].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT12'] + '">';
            jqTds[19].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT13'] + '">';
            jqTds[20].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT14'] + '">';
            jqTds[21].innerHTML = '<input type="text" class="form-control input-small input-edit" value="' + aData['DT15'] + '">';
            jqTds[0].innerHTML = '<div style="width:80px"><a class="edit btn btn-sm btn-clean btn-icon btn-icon-md" href="" title="Save" href=""><i class="la la-save"></i></a> <a class="cancel btn btn-sm btn-clean btn-icon btn-icon-md" href=""><i class="la la-close"></i></a></div>';

            var eTh = $('.dataTables_scrollHeadInner table thead tr th');
            for (var x=0; x<eTh.length; x++){
                var oldText = $(eTh[x]).html();
                if (x > 1){
                    $(eTh[x]).html('<div style="width:100px;">'+oldText+'</div>');
                } else {
                    $(eTh[x]).html('<div style="width:80px;">'+oldText+'</div>');
                }
            }
        }
        
        var nEditing = null;
        var nNew = false;

        table.on('click', '.cancel', function (e) {
            e.preventDefault();
            if (nNew) {
                oTable.fnDeleteRow(nEditing);
                nEditing = null;
                nNew = false;
            } else {
                restoreRow(oTable, nEditing);
                nEditing = null;
            }
        });


        table.on('click', '.edit', function (e) {
            e.preventDefault();
            nNew = false;
            
            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];

            if (nEditing !== null && nEditing != nRow) {
                /* Currently editing - but not this row - restore the old before continuing to edit mode */
                restoreRow(oTable, nEditing);
                editRow(oTable, nRow);
                nEditing = nRow;
            } else if (nEditing == nRow && this.title == "Save") {
                /* Editing this row and want to save it */
                saveRow(oTable, nEditing);
                nEditing = null;
                alert("Updated!");
            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow);
                nEditing = nRow;
            }
            //$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            //$('body').addClass('kt-aside--minimize');
            //oTable.columns.adjust().draw();
            
            
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