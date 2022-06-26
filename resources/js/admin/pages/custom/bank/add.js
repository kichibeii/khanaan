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
