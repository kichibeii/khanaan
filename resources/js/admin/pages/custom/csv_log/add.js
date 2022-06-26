"use strict";

// Class definition
var KTAdd = function () {
	// Base elements
	var formEl;
	var validator;

	var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }

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

		$('.kt_datepicker').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
			templates: arrows,
			format: "yyyy-mm-dd"
        });
		
		$('#ship_id').select2({
			placeholder: 'Pilih Kapal',
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
