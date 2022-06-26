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
		var btn = $('[data-ktwizard-type="action-submit"]');
		btn.on('click', function(e) {
            e.preventDefault();
            formEl.submit();
            
            /*
			if (validator.form()) {
				// See: src\js\framework\base\app.js
				KTApp.progress(btn);
				//KTApp.block(formEl);
                // See: http://malsup.com/jquery/form/#ajaxSubmit
                
				formEl.ajaxSubmit({
					success: function() {
						KTApp.unprogress(btn);
						//KTApp.unblock(formEl);

						swal.fire({
							"title": "",
							"text": "The application has been successfully submitted!",
							"type": "success",
							"confirmButtonClass": "btn btn-secondary"
						});
					}, error: function(resp){
						swal.fire({
                            "title": "",
                            "text": "Ada beberapa kesalahan dalam form yang anda isi. Silahkan perbaiki terlebih dahulu.",
                            "type": "error",
                            "buttonStyling": false,
                            "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                        });
					}
                });
            }
            */
		});
	}

	var initConfig = function() {
		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
		});

		/* START TEAM */
        $(document).on('click', '.add-team', function(){
            
            var totalTr = $('#table-team tbody tr').length;
            if (totalTr == 0){
                newId = 1;   
            } else {
                var newId = parseInt($('#table-team tbody tr:last-child').attr('id')) + 1;    
            }

            var eHtml = "<tr id='"+newId+"'>" + 
                    "<td></td>" +
                    "<td>" +
                    "<input type='text' required class='form-control' name='team[username][]' data-id='"+newId+"' maxlength='100'>" +
                    "</td>" +
                    "<td>" +
                    "<input type='email' required class='form-control' name='team[email][]' data-id='"+newId+"'>" +
                    "</td>" +
                    "<td>" +
                    "<select name='team[role_id][]' class='role_id form-control' data-id='"+newId+"'></select>" +
                    "</td>" +
                    "<td>" +
                    "<input type='password' required class='form-control' name='team[password][]' data-id='"+newId+"'>" +
                    "</td>" +
                    "<td class='text-center'></td>" +
                    "</tr>";

            $('#table-team tbody').append(eHtml);

            $('.role_id').select2({
                placeholder: 'Pilih User Group',
                width: '100%',

                language: {
                    searching: function() {
                        return "Searching...";
                    }
                },
                ajax: {
                    url: '/role/get-data-array',
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

            rearrangeTeam();
        });

        $(document).on('click', '.delete-team', function(){
            var totalTr = $('#table-team tbody tr').length;
            var that = $(this);

            if (totalTr > 1){
                if (confirm('Apakah anda serius akan menghapus data ini?')){
                    that.parent().parent().remove();
                    rearrangeTeam();
                }
            } else {
                alert('Anda tidak bisa menghapus semua data!');
            }
            
        });

        function rearrangeTeam()
        {
            var totalTr = $('#table-team tbody tr').length;
            if (totalTr > 1){
                $('.add-team').remove();
                for (var x=0; x<totalTr; x++){
                    var eHtml = "<a href='javascript:;' class='delete-team'><i class='fa fa-minus'></i></a> ";
                    if (x == totalTr-1){
                        eHtml += "&nbsp;<a href='javascript:;' class='add-team'><i class='fa fa-plus'></i></a>";
                    }
                    $('#table-team tbody tr').eq(x).find('td:last-child').html( eHtml );
                    $('#table-team tbody tr').eq(x).find('td:first-child').html( (parseInt(x)+1) );
                }
            } else {
                $('#table-team tbody tr td:last-child').html( "<a href='javascript:;' class='add-team'><i class='fa fa-plus'></i></a>" );
                $('#table-team tbody tr td:first-child').html( "1" );
            }
            
        }

        var arrPositionProject = new Array();
        if ($('#table-team').attr('data-add') == '0'){
            $('.role_id').select2({
                placeholder: 'Pilih User Group',
                width: '100%',

                language: {
                    searching: function() {
                        return "Searching...";
                    }
                },
                ajax: {
                    url: '/role/get-data-array',
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

            rearrangeTeam();


        } else {
            $('.add-team').click();
        }

        /* END TEAM */
	}

	

	return {
		// public functions
		init: function() {
			formEl = $('#kt_form');

			//initValidation();
			initConfig();
			initSubmit();
		}
	};
}();

jQuery(document).ready(function() {
	KTAdd.init();
});
