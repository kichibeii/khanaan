"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

    

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

        $('#type').select2();

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
                url: '/ship/'+data.id+'/get-setting-trend'
            }).then(function (data) {

                var eHtml = "";
                if (data.length > 0){
                    eHtml += "<div class='kt-checkbox-inline'>";
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
                $("#div-column div").html(eHtml);
            });

            $('#div-column').removeClass('d-none');
        });

        $('#ship_id').on("select2:clear", function(e) {
            var data = e.params.data;
            $('#div-column').addClass('d-none');
            $('#div-column div').html('');
        });

        var chart = AmCharts.makeChart("kt_amcharts_6", {
            "type": "serial",
            "theme": "light",
            "marginRight": 0,
            "marginLeft": 0,
            "autoMarginOffset": 20,
            "mouseWheelZoomEnabled": true,
            "legend": {
                "equalWidths": true,
                "useGraphSettings": false,
                "valueAlign": "left",
                //"valueWidth": 100
            },
            "dataDateFormat": "YYYY-MM-DD",
            "valueAxes": [{
                "id": "v1",
                "axisAlpha": 0,
                "position": "left",
                "ignoreAxisWidth": true
            }],
            "balloon": {
                "borderThickness": 1,
                "shadowAlpha": 0
            },
            "graphs": [],
            /*
            "chartScrollbar": {
                "graph": "g1",
                "oppositeAxis": false,
                "offset": 30,
                "scrollbarHeight": 80,
                "backgroundAlpha": 0,
                "selectedBackgroundAlpha": 0.1,
                "selectedBackgroundColor": "#888888",
                "graphFillAlpha": 0,
                "graphLineAlpha": 0.5,
                "selectedGraphFillAlpha": 0,
                "selectedGraphLineAlpha": 1,
                "autoGridCount": true,
                "color": "#AAAAAA"
            },
            */
            "chartCursor": {
                "pan": true,
                "valueLineEnabled": true,
                "valueLineBalloonEnabled": true,
                "cursorAlpha": 1,
                "cursorColor": "#258cbb",
                "limitToGraph": "g1",
                "valueLineAlpha": 0.2,
                "valueZoomable": true
            },
            "valueScrollbar": {
                "oppositeAxis": false,
                "offset": 50,
                "scrollbarHeight": 10
            },
            "categoryField": "date",
            "categoryAxis": {
                "dateFormats": [{
                    "period": "DD",
                    "format": "DD"
                }, {
                    "period": "WW",
                    "format": "MMM DD"
                }, {
                    "period": "MM",
                    "format": "MMM"
                }, {
                    "period": "YYYY",
                    "format": "YYYY"
                }],
                "dashLength": 1,
                "minorGridEnabled": true,
                "autoRotateAngle": 90,
                "autoRotateCount": 1
            },
            "export": {
                "enabled": true
            },
            "dataProvider": []
        });

        chart.addListener("rendered", zoomChart);

        zoomChart();

        function zoomChart() {
            chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
        }

        $(document).on('click', '#btn-display', function(){
            
            if ($('#ship_id').val() == null || $('#client_id').val() == null){
                alert('Silahkan lengkapi semua isian form!');
                return false;
            }
            
            //$('#div-content').html('');

            $.ajax({
                type: 'POST',
                url: '/trend/get-data',
                data: $('#kt_form').serialize()
            }).then(function (data) {
                
                
                $('#kt_portlet_tools_2').addClass('kt-portlet--collapse');
                $('#kt_portlet_tools_2 .kt-portlet__body').attr('style', 'display: none; overflow: hidden; padding-top: 0px;');
                //$('#btn-collapse').click();
                chart.graphs = data.graph;
                chart.dataProvider = data.data;
                chart.validateData();
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