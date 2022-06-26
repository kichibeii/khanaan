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

		$(document).on('click', '.add-to-list', function(){
			var colorSelected = $('#color_id option:selected');
			var sizeSelected = $('#size_id option:selected');
			var getColor = colorSelected.data('color');
			var getSize = sizeSelected.data('size');
			var getNameColor = colorSelected.data('namecolor');
			var getNameSize = sizeSelected.data('namesize');
			var getQty = parseInt($('#qty').val().replace(/\./g,''));
			var no = $('.list .list-detail:last-child').data('number') + 1;
            var getTr = $('#tr[data-tr="'+getColor+'-'+getSize+'"]');
			
			if (getQty != ''){

                if (getTr.length > 0){
                    alert ('warna dan ukuran tidak boleh sama!');
                } else {

                    var eHtml = '';
                    eHtml +=    "<tr class='list-detail' id='tr' data-tr='"+getColor+"-"+getSize+"' data-number="+no+">"+
                                "<td class='hidden'><input type='hidden' value="+getNameColor+"  name='color'>"+getNameColor+"</td>"+
                                "<td>"+getNameSize+"</td>"+
                                "<td><input type='hidden' name='qty["+getColor+"]["+getSize+"]' value="+getQty+">"+getQty+"</td>"+
                                "<td align='center'>"+
                                "<a href='javascript:;' data-close='1' class='pilihan-button remove-list' data-id='"+no+"'><i class='la la-trash'></i></a> "+
                                "</td>"+
                                "</tr>";
                    $('.list').append(eHtml);

                    $('#qty').val('');
                }
            }
		})

		/*
		$('.add-to-list').click(function (e) {

            var getColor = $('#color option:selected').data('color');
            var getSize = $('#size option:selected').data('size');
            var getNameColor = $('#color option:selected').data('namecolor');
            var getNameSize = $('#size option:selected').data('namesize');
            var getQty = $('#qty').val();
            var no = $('.list .list-detail:last-child').data('number') + 1;
            var getTr = $('#tr[data-tr="'+getColor+'-'+getSize+'"]');

            if (getQty != ''){

                if (getTr.length > 0){
                    alert ('warna dan ukuran tidak boleh sama!');
                } else {

                    var eHtml = '';
                    eHtml +=    "<tr class='list-detail' id='tr' data-tr='"+getColor+"-"+getSize+"' data-number="+no+">"+
                                "@if(!$isColorize)<td class='hidden'><input type='hidden' value="+getNameColor+"  name='color'>"+getNameColor+"</td>"+
                                "@else <td><input type='hidden' value="+getNameColor+" >"+getNameColor+"</td>"+
                                "@endif"+
                                "<td>"+getNameSize+"</td>"+
                                "<td><input type='hidden' name='qty["+getColor+"]["+getSize+"]["+getQty+"]' value="+getQty+">"+getQty+"</td>"+
                                "<td align='center'>"+
                                "<a href='javascript:;' data-close='1' class='pilihan-button remove-list' data-id='"+no+"'>"+"<span class='glyphicon glyphicon-remove'></span>"+"</a> "+
                                "</td>"+
                                "</tr>";
                    $('.list').append(eHtml);

                    $('#qty').val('');
                }
            }

		});
		*/

        $(document).on('click', '.remove-list', function(){
			if (confirm('Apakah anda serius akan menghapus data ini?')) {
                var that = $(this);
            	that.parent().parent().remove();
            }
            
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
