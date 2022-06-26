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

		$('.select2').select2();

		$('.kt_datepicker').datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'dd-mm-yyyy'
		});

		$('.is_money').maskMoney({
			thousands: '.',
			decimal: ',',
			precision: 0
		});

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
