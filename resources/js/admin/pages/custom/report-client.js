"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

    var createPopUp = function(url, w, h) {
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        return window.open(url, 'share', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    }

	var initTable1 = function() {

        $(document).on('click', '#btn-print, #btn-excel', function(){
            var that = $(this);

            var url = $('#kt_form').serialize();
            
            if (that.attr('id') == 'btn-print'){
                createPopUp('/report/print?'+url, 800, 600);
            } else {
                document.location.href = '/report/export?'+url;
            }
            return false;
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
		});

        $('.kt_daterangepicker').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            locale: {
                format: "DD/MM/YYYY"
            },
            dateLimit: { days: 31 },
            
        });

        $('#ship_id').select2({
            placeholder: 'Pilih Kapal',
            allowClear: true,
            width: '100%',

            language: {
                searching: function() {
                    return "Searching...";
                }
            },
            ajax: {
                url: '/ship/get-data-by-client-array',
                dataType: 'json',
                type: 'POST',
                data:{
                    client_id: $('#client_id').val(), // Second add quotes on the value.
                },
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        var arrColumn = new Array();
        $('#ship_id').on("select2:select", function(e) {
            var data = e.params.data;

            $.ajax({
                type: 'GET',
                url: '/ship/'+data.id+'/get-setting'
            }).then(function (data) {

                var eHtml = "";
                if (data.length > 0){
                    eHtml += "<div class='kt-checkbox-inline'>";
                    for (var x=0; x<data.length; x++){
                        eHtml += "<div class='row'>";
                        for (var x=0; x<data.length; x++){
                            eHtml += "<div class='col-md-3'>";
                            eHtml += "<div class='kt-checkbox-inline'>";
                            eHtml += "<label class='kt-checkbox kt-checkbox--tick kt-checkbox--success'>" +
                                "<input type='checkbox' checked name='columns["+data[x].id+"]' value='"+data[x].text+"'> " + data[x].text + "" + 
                                "<input type='hidden' class='form_type' name='footers["+data[x].id+"]' value='"+data[x].footer+"'> " + 
                                "<span></span> " +
                                "</label>";
                            eHtml += "</div>";
                            eHtml += "</div>";
                        }
                        eHtml += "</div>";
                    }
                    eHtml += "</div>";
                }
                $("#div-column div").html(eHtml);
            });

            $('#div-column').removeClass('d-none');
        });

        $('#ship_id').on("select2:clear", function(e) {
            var data = e.params.data;
            $('#div-column').addClass('d-none');
            $('#div-column div').html('');
        });

        var tableData;
        $(document).on('click', '#btn-display', function(){
            if ($('#ship_id').val() == null){
                alert('Silahkan lengkapi semua isian form!');
                return false;
            }

            $('#div-content').html('');
            $.ajax({
                type: 'POST',
                url: '/report/get-filter',
                data: $('#kt_form').serialize()
            }).then(function (data) {
                $('#kt_portlet_tools_2').addClass('kt-portlet--collapse');
                $('#kt_portlet_tools_2 .kt-portlet__body').attr('style', 'display: none; overflow: hidden; padding-top: 0px;');
                //$('#btn-collapse').click();

                var timeNow = new Date().getTime();

                var eTable = '<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_'+timeNow+'">';
                eTable += '<thead><tr></tr></thead>';
                eTable += '</table>';
                $('#div-content').html(eTable);
                var table = $('#kt_table_'+timeNow);

                
                var str;
                var arrFooter = new Array;
                var colInfo = new Array;
                $.each(data, function (k, colObj) {
                    str = '<th>' + colObj.name + '</th>';
                    $(str).appendTo('#kt_table_'+timeNow+'>thead>tr');
                    arrFooter[k] = colObj.footer;
                    colInfo[k] = colObj.name;
                });

                if (arrFooter.length > 0){
                    var eFooter = "";
                    var eFooter = "<tfoot>" +
                                    "<tr>" +
                                    "<th colspan='3' style='text-align:right'>Total/Rata-rata:</th>";
                    $.each(arrFooter, function (k, v) {
                        if (k > 2){
                            eFooter += "<th class='text-right'></th>";
                        }
                    });
                    eFooter += "</tr>" + 
                                "</tfoot>";

                    $(eFooter).appendTo('#kt_table_'+timeNow);
                }

                table.DataTable({
                    //scrollY: '50vh',
                    scrollX: true,
                    scrollCollapse: true,
                    ordering: false,
                    info: false,
                    searching: false,
                    pageLength: 25,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/report/get-data',
                        data: function(d){
                            d.ship_id = $('#ship_id').val();
                            d.period = $('#period').val();
                            d.sampling = $('.sampling:checked').val();
                            d.sort_by = $('.sort_by:checked').val();
                            d.column_checked = data;
                        },
                        type: "POST"
                    },
                    order: [
                        [1, "desc"]
                    ],
                    columns: data,
                    columnDefs: [
                        {
                            targets: [ 0 ],
                            visible: false,
                            searchable: false
                        },
                        {
                            targets: [ 0 ],
                            visible: false,
                            searchable: false
                        }
                    ],

                    footerCallback: function ( row, data, start, end, display, grand_total ) {
                        //var api = this.api(), data;
                        var api = this.api();
                        var json = api.ajax.json();
                        
                        // Remove the formatting to get integer data for summation
                        var intVal = function ( i ) {
                            //return parseFloat(i.replace(/,/g, ''));
                            return typeof i === 'string' ? 
                                i.replace(/,/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };

                        var number_format = function (number, decimals, dec_point, thousands_sep) {
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

                        if (arrFooter.length > 0){
                            var arrPageTotal = new Array;
                            var columnData = new Array;
                            $.each(arrFooter, function (k, v) {
                                if (v == '1' || v == '2'){
                                    arrPageTotal[k] = api
                                        .column( k, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                            a = parseFloat(intVal(a));
                                            b = parseFloat(intVal(b));
                                            return (a + b).toFixed(2);
                                        }, 0 );
                                    
                                    var total = 0;
                                    if (v == '1'){
                                        total = arrPageTotal[k];
                                    } else {
                                        columnData[k] = api
                                            .column( k )
                                            .data();

                                        total = arrPageTotal[k] / columnData[k].count();
                                    }

                                    $( api.column( k ).footer() ).html(
                                        (v == '1' ? 'Total ' : 'Rata-rata ') + number_format(total, 2) +' <br>dari '+ number_format(json['footers'][colInfo[k]], 2)
                                    );
                                }
                            });
                        }
                    },
                });

                $('div.dataTables_length select').addClass( 'custom-select custom-select-sm form-control form-control-sm' );
            });
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