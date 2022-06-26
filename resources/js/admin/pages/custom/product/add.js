"use strict";

// Class definition
var KTAdd = function () {
	// Base elements
	var formEl;
	var validator;

	var initValidation = function() {
		validator = formEl.validate({
			// Validate only visible fields
			ignore: ":hidden",

			// Validation rules
			rules: {
				name: {
					required: true
				}
			},

			// Display error
			invalidHandler: function(event, validator) {
				KTUtil.scrollTop();

				swal.fire({
					"title": "",
					"text": "Ada beberapa kesalahan dalam form yang anda isi. Silahkan perbaiki terlebih dahulu.",
					"type": "error",
					"buttonStyling": false,
					"confirmButtonClass": "btn btn-brand btn-sm btn-bold"
				});
			},

			// Submit valid form
			submitHandler: function (form) {

			}
		});
	}

	var initSubmit = function() {
		
		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
		});

		$('.is_money').maskMoney({
			thousands: '.',
			decimal: ',',
			precision: 0
		});

		$('#brand_id').select2({
			placeholder: 'Pilih Brand',
			width: '100%',
			language: {
				searching: function() {
					return "Search...";
				}
			},
			ajax: {
				url: '/brand/get-data-array',
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

		$('#tags').select2({
			placeholder: 'Cari Keyword',
			width: '100%',
			tags: true,
			tokenSeparators: [',', ' '],
			language: {
				searching: function() {
					return "Search...";
				}
			},
			ajax: {
				url: '/tag/get-data-array',
				dataType: 'json',
				type: 'GET',
				delay: 250,
				/*
				data: function (params) {
					return {
						q: params.term, // search term
						page: params.page
					};
				},
				*/
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache: true
			}
		});

		$('.kt_datetimepicker').datetimepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'dd-mm-yyyy hh:ii'
		});
		
		$("#title").keyup(function(){
			var Text = $(this).val();
			Text = Text.toLowerCase();
			Text = Text.replace(/[^a-zA-Z0-9-_]+/g,'-');
			$("#slug").val(Text);
		});

		$(document).on('click', '.colors', function () {
			let colorCheckeds = $('.colors:checked');
			let colorUncheckeds = $('.colors:not(:checked)');

			for (var x=0; x<=colorUncheckeds.length; x++){
				$('#form-group-'+$(colorUncheckeds[x]).val()).addClass('d-none');
			}

			if (colorCheckeds.length > 0){
				for (var x=0; x<=colorCheckeds.length; x++){
					$('#form-group-'+$(colorCheckeds[x]).val()).removeClass('d-none');
				}
			} else {
				$('.form-group-color').addClass('d-none');
			}
		});

		//$('.colors').eq(0).click();

		$(document).on('click', '.btnCheckSize', function () {
            var colorId = $(this).data('id');
            var eCheckbox = $( ".size_color_"+colorId+":checked" );
            var arrExists = new Array();
            var arrNewChecked = new Array();
            if ($('#table-'+colorId+' thead tr th').length > 1) {
                for (var y=1; y<$('#table-'+colorId+' thead tr th').length; y++) {
                    arrExists[y] = $('#table-'+colorId+' thead tr th').eq(y).attr('id');
                }
            }

            for (var x=0; x<eCheckbox.length; x++) {
                var sizeId = eCheckbox.eq(x).val();
                arrNewChecked[x] = colorId+'-'+sizeId;
                if ($('#table-'+colorId+' thead tr th#'+colorId+'-'+sizeId).length == 0) {
                    $('#table-'+colorId+' thead tr').append('<th id="'+colorId+'-'+sizeId+'">'+eCheckbox.eq(x).data('title')+'</th>');
                    $('#table-'+colorId+' tbody tr').append('<td id="x'+colorId+'-'+sizeId+'"><input type="text" class="form-control" name="size['+colorId+']['+sizeId+']"></td>');
                }
            }

            //console.log(arr_diff(arrExists, arrNewChecked));

            var arrNotSelected = new Array();
            if (arrExists.length > 0) {
                $.each(arrExists, function(i, n){
                    if (n != undefined) {
                        if (jQuery.inArray( n, arrNewChecked ) === -1) {
                            arrNotSelected[i] = n;
                        }
                    }
                });
            }

            if (arrNotSelected.length > 0) {
                $.each(arrNotSelected, function(i, n){
                    if (n != undefined) {
                        $('#table-'+colorId+' thead tr th#'+n).remove();
                        $('#table-'+colorId+' tbody tr td#x'+n).remove();
                    }
                });
            }
        });

		$(".main-image").dropzone({
			url: "/product/dropzone",
			addRemoveLinks: true,
			thumbnailWidth: null,
			thumbnailHeight: null,
			uploadMultiple: false,
			//maxFiles:1,
			acceptedFiles: 'image/*',
            accept: function(file, done) {
                var extension = file.name.substring(file.name.lastIndexOf('.')+1);
                if (extension == "exe" || extension == "bat") {
                    alert("Error! File(s) of these type(s) are not accepted.");
                    done("Error! File(s) of these type(s) are not accepted.");
                } else { done(); }
            },
			init: function() {
				if (action == 'edit'){
					var thisDropzone = this;
					var eId = $(thisDropzone.element).data('id');
					var mockFile = {name: $(thisDropzone.element).data('image'), size: ''};
					thisDropzone.emit("addedfile", mockFile);
					thisDropzone.emit("thumbnail", mockFile, $(thisDropzone.element).data('image')); //76110
					thisDropzone.files.push(mockFile);
					thisDropzone.emit("complete", mockFile);

					$('.dropzone[data-id="'+eId+'"]').find('img').attr({width: '100%', height: '100%'});
					$('.dropzone[data-id="'+eId+'"]').find('.dz-image').attr('style', 'width: 100%;height: auto');
				}
				
					
				this.on("addedfile", function (file) {
					var _this = this;
					if ($.inArray(file.type, ['image/jpeg', 'image/jpg', 'image/png']) == -1) {
						_this.removeFile(file);
					}
				});
				this.on("thumbnail", function(file, dataUrl) {
					var id = $($(this)[0].element).data('id');
					//console.log(id);
					$('.dropzone[data-id="'+id+'"]').find('img').attr({width: '100%', height: '100%'});
					//$('.dz-image').last().find('img').attr({width: '100%', height: '100%'});
					//$('.dz-image').last().find('img').addClass('img-responsive');
				}),
				this.on("success", function(file) {
					$('.dz-image').css({"width":"100%", "height":"auto"});
					//$('.dz-image').addClass('img-responsive');
				}),
				this.on("sending", function(file, xhr, formData){
					var id = $($(this)[0].element).data('id');
					formData.append("id", id);
				}),
				this.on("removedfile", function(file, xhr, formData){
					var id = $($(this)[0].element).data('id');  
					$('#image-'+id).val('');
				}),
				this.on('error', function(file, response) {
					var id = $($(this)[0].element).data('id');
					$('.dropzone[data-id="'+id+'"]').find('.dz-preview').remove();
					alert(response.error);
				});
			},
			success: function (file, response) {
				if ($($(this)[0].element).find('.dz-preview').length > 1){
					$($(this)[0].element).find('.dz-preview:eq(0)').remove();
				}
				
				var imgName = response.url;
				$('#image-'+response.id).val(imgName);
				file.previewElement.classList.add("dz-success");
			},
			error: function (file, response) {
				$('#image-'+response.id).val('');
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$(".upload-photo").dropzone({
            uploadMultiple: true,
            addRemoveLinks: true,
            dictDefaultMessage: '',
            url: "/product/uploadImage",
            method: "post",
            thumbnailWidth: 76,
            thumbnailHeight: 110,
            maxFilesize: 1, // MB
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
            accept: function(file, done) {
                var extension = file.name.substring(file.name.lastIndexOf('.')+1);
                if (extension == "exe" || extension == "bat") {
                    alert("Error! File(s) of these type(s) are not accepted.");
                    done("Error! File(s) of these type(s) are not accepted.");
                } else { done(); }
            },
            init: function() {
                this.on("removedfile", function(file, xhr, formData){
					var eId = this.element.dataset.id;
                    $('#hidden-'+eId+' input[data-filename="'+file.name+'"]').remove();
                });

                this.on("success", function(file, res){
                    var eId = this.element.dataset.id;
                    $.each(res.files, function(i, n){
                        if (n != undefined) {
                            if ($('#hidden-'+eId+' input[data-val="'+n.storage+'"]').length == 0) {
                                $('#hidden-'+eId).append('<input name="images['+eId+'][]" type="hidden" data-val="'+n.storage+'" data-filename="'+n.filename+'" value="'+n.storage+'">');
                            }
                        }
                    });
                });

                this.on("error", function(file){
                    if (!file.accepted)
                        this.removeFile(file);
                });
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            sending: function(file, xhr, formData){
            }
		});
		
		/* START DISCOUNT */
		$(document).on('click', '.add-discount', function(){
			var tableid = '#table-discount';
            var totalTr = $(tableid+' tbody tr').length;
            if (totalTr == 0){
                newId = 1;   
            } else {
                var newId = parseInt($(tableid + ' tbody tr:last-child').attr('id')) + 1;    
			}
			
            var eHtml = "<tr id='"+newId+"'>" + 
					"<td>" +
					"<input type='number' min='0' class='form-control ' name='discounts[priority][]'>" +
					"</td>" +
					"<td>" +
					"<input type='text' class='form-control text-right is_money' name='discounts[price][]'>" +
                    "</td>" +
                    "<td>" +
                    "<input type='text' class='form-control kt_datetimepicker' name='discounts[date_start][]'>" +
                    "</td>" +
                    "<td>" +
                    "<input type='text' class='form-control kt_datetimepicker' name='discounts[date_end][]'>" +
                    "</td>" +
                    "<td class='text-center'></td>" +
                    "</tr>";

			$(tableid + ' tbody').append(eHtml);
			rearrangeDiscount();

			$('.is_money').maskMoney({
				thousands: '.',
				decimal: ',',
				precision: 0
			});

			$('.kt_datetimepicker').datetimepicker({
				todayHighlight: true,
				autoclose: true,
				format: 'dd-mm-yyyy hh:ii'
			});
		});

		$(document).on('click', '.delete-discount', function(){
			var tableid = '#table-discount';

            var totalTr = $(tableid + ' tbody tr').length;
            var that = $(this);

            if (totalTr > 1){
                if (confirm('Apakah anda serius akan menghapus data ini?')){
                    that.parent().parent().remove();
					rearrangeDiscount();
                }
            } else {
                alert('Anda tidak bisa menghapus semua data!');
            }
            
		});

		function rearrangeDiscount()
        {
			var tableid = '#table-discount';
            var totalTr = $(tableid + ' tbody tr').length;
            if (totalTr > 1){
                $('.add-discount').remove();
                for (var x=0; x<totalTr; x++){
                    var eHtml = "<a href='javascript:;' class='delete-discount'><i class='fa fa-minus'></i></a> ";
                    if (x == totalTr-1){
                        eHtml += "&nbsp;<a href='javascript:;' class='add-discount'><i class='fa fa-plus'></i></a>";
                    }
                    $(tableid + ' tbody tr').eq(x).find('td:last-child').html( eHtml );
                }
            } else {
                $(tableid + ' tbody tr td:last-child').html( "<a href='javascript:;' class='add-discount'><i class='fa fa-plus'></i></a>" );
            }
		}

		if ($("#table-discount").attr('data-add') == '0'){
			rearrangeDiscount();
		} else {
			$('.add-discount').click();
		}

		/* END DISCOUNT */


		/* START NEW RELEASE */
		$(document).on('click', '.add-new_release', function(){
			var tableid = '#table-new_release';
            var totalTr = $(tableid+' tbody tr').length;
            if (totalTr == 0){
                newId = 1;   
            } else {
                var newId = parseInt($(tableid + ' tbody tr:last-child').attr('id')) + 1;    
			}
			
            var eHtml = "<tr id='"+newId+"'>" + 
					"<td>" +
					"<input type='number' min='0' class='form-control ' name='new_releases[priority][]'>" +
					"</td>" +
                    "<td>" +
                    "<input type='text' class='form-control kt_datetimepicker' name='new_releases[date_start][]'>" +
                    "</td>" +
                    "<td>" +
                    "<input type='text' class='form-control kt_datetimepicker' name='new_releases[date_end][]'>" +
                    "</td>" +
                    "<td class='text-center'></td>" +
                    "</tr>";

			$(tableid + ' tbody').append(eHtml);
			rearrangeNewRelease();

			$('.kt_datetimepicker').datetimepicker({
				todayHighlight: true,
				autoclose: true,
				format: 'dd-mm-yyyy hh:ii'
			});
		});

		$(document).on('click', '.delete-new_release', function(){
			var tableid = '#table-new_release';

            var totalTr = $(tableid + ' tbody tr').length;
            var that = $(this);

            if (totalTr > 1){
                if (confirm('Apakah anda serius akan menghapus data ini?')){
                    that.parent().parent().remove();
					rearrangeNewRelease();
                }
            } else {
                alert('Anda tidak bisa menghapus semua data!');
            }
            
		});

		function rearrangeNewRelease()
        {
			var tableid = '#table-new_release';
            var totalTr = $(tableid + ' tbody tr').length;
            if (totalTr > 1){
                $('.add-new_release').remove();
                for (var x=0; x<totalTr; x++){
                    var eHtml = "<a href='javascript:;' class='delete-new_release'><i class='fa fa-minus'></i></a> ";
                    if (x == totalTr-1){
                        eHtml += "&nbsp;<a href='javascript:;' class='add-new_release'><i class='fa fa-plus'></i></a>";
                    }
                    $(tableid + ' tbody tr').eq(x).find('td:last-child').html( eHtml );
                }
            } else {
                $(tableid + ' tbody tr td:last-child').html( "<a href='javascript:;' class='add-new_release'><i class='fa fa-plus'></i></a>" );
            }
		}

		if ($("#table-new_release").attr('data-add') == '0'){
			rearrangeNewRelease();
		} else {
			$('.add-new_release').click();
		}

		/* END NEW RELEASE */


		/* START BEST SELLER */
        $(document).on('click', '.add-best_seller', function(){
            var tableid = '#table-best_seller';
            var totalTr = $(tableid+' tbody tr').length;
            if (totalTr == 0){
                newId = 1;   
            } else {
                var newId = parseInt($(tableid + ' tbody tr:last-child').attr('id')) + 1;    
            }
            
            var eHtml = "<tr id='"+newId+"'>" + 
                    "<td>" +
                    "<input type='number' min='0' class='form-control ' name='best_sellers[priority][]'>" +
                    "</td>" +
                    "<td>" +
                    "<input type='text' class='form-control kt_datetimepicker' name='best_sellers[date_start][]'>" +
                    "</td>" +
                    "<td>" +
                    "<input type='text' class='form-control kt_datetimepicker' name='best_sellers[date_end][]'>" +
                    "</td>" +
                    "<td class='text-center'></td>" +
                    "</tr>";

            $(tableid + ' tbody').append(eHtml);
            rearrangeBestSeller();

            $('.kt_datetimepicker').datetimepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'dd-mm-yyyy hh:ii'
            });
        });

        $(document).on('click', '.delete-best_seller', function(){
            var tableid = '#table-best_seller';

            var totalTr = $(tableid + ' tbody tr').length;
            var that = $(this);

            if (totalTr > 1){
                if (confirm('Apakah anda serius akan menghapus data ini?')){
                    that.parent().parent().remove();
                    rearrangeBestSeller();
                }
            } else {
                alert('Anda tidak bisa menghapus semua data!');
            }
            
        });

        function rearrangeBestSeller()
        {
            var tableid = '#table-best_seller';
            var totalTr = $(tableid + ' tbody tr').length;
            if (totalTr > 1){
                $('.add-best_seller').remove();
                for (var x=0; x<totalTr; x++){
                    var eHtml = "<a href='javascript:;' class='delete-best_seller'><i class='fa fa-minus'></i></a> ";
                    if (x == totalTr-1){
                        eHtml += "&nbsp;<a href='javascript:;' class='add-best_seller'><i class='fa fa-plus'></i></a>";
                    }
                    $(tableid + ' tbody tr').eq(x).find('td:last-child').html( eHtml );
                }
            } else {
                $(tableid + ' tbody tr td:last-child').html( "<a href='javascript:;' class='add-best_seller'><i class='fa fa-plus'></i></a>" );
            }
        }

        if ($("#table-best_seller").attr('data-add') == '0'){
            rearrangeBestSeller();
        } else {
            $('.add-best_seller').click();
        }

        /* END BEST SELLER */





		var btn = $('[data-ktwizard-type="action-submit"]');
		btn.on('click', function(e) {
            e.preventDefault();
            formEl.submit();
		});
	}

	return {
		// public functions
		init: function() {
			formEl = $('#kt_form');

			//initValidation();
			initSubmit();
		}
	};
}();

jQuery(document).ready(function() {
	KTAdd.init();
});
