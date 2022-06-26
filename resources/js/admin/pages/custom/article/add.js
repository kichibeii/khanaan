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

		ClassicEditor
			.create( document.querySelector( '#description' ), {
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				link: {
					// Automatically add target="_blank" and rel="noopener noreferrer" to all external links.
					addTargetToExternalLinks: true,

					// Let the users control the "download" attribute of each link.
					decorators: [
						{
							mode: 'manual',
							label: 'Downloadable',
							attributes: {
								download: 'download'
							}
						}
					]
				}
			} )
			.then( editor => {
				//console.log( editor );
			} )
			.catch( error => {
				//console.error( error );
			} );

		class MyUploadAdapter {
			constructor( loader ) {
				// The file loader instance to use during the upload.
				this.loader = loader;
			}
		
			// Starts the upload process.
			upload() {
				return this.loader.file
					.then( file => new Promise( ( resolve, reject ) => {
						this._initRequest();
						this._initListeners( resolve, reject, file );
						this._sendRequest( file );
					} ) );
			}
		
			// Aborts the upload process.
			abort() {
				if ( this.xhr ) {
					this.xhr.abort();
				}
			}
		
			// Initializes the XMLHttpRequest object using the URL passed to the constructor.
			_initRequest() {
				const xhr = this.xhr = new XMLHttpRequest();
		
				// Note that your request may look different. It is up to you and your editor
				// integration to choose the right communication channel. This example uses
				// a POST request with JSON as a data structure but your configuration
				// could be different.
				xhr.open( 'POST', '/dashboard/image-upload', true );
				xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
				//xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr.responseType = 'json';
			}  
		
			// Initializes XMLHttpRequest listeners.
			_initListeners( resolve, reject, file ) {
				const xhr = this.xhr;
				const loader = this.loader;
				const genericErrorText = `Gagal upload file: ${ file.name }.`;
		
				xhr.addEventListener( 'error', () => reject( genericErrorText ) );
				xhr.addEventListener( 'abort', () => reject() );
				xhr.addEventListener( 'load', () => {
					const response = xhr.response;
		
					// This example assumes the XHR server's "response" object will come with
					// an "error" which has its own "message" that can be passed to reject()
					// in the upload promise.
					//
					// Your integration may handle upload errors in a different way so make sure
					// it is done properly. The reject() function must be called when the upload fails.
					if ( !response || response.error ) {
						return reject( response && response.error ? response.error.message : genericErrorText );
					}
		
					// If the upload is successful, resolve the upload promise with an object containing
					// at least the "default" URL, pointing to the image on the server.
					// This URL will be used to display the image in the content. Learn more in the
					// UploadAdapter#upload documentation.
					resolve( {
						default: response.url
					} );
				} );
		
				// Upload progress when it is supported. The file loader has the #uploadTotal and #uploaded
				// properties which are used e.g. to display the upload progress bar in the editor
				// user interface.
				if ( xhr.upload ) {
					xhr.upload.addEventListener( 'progress', evt => {
						if ( evt.lengthComputable ) {
							loader.uploadTotal = evt.total;
							loader.uploaded = evt.loaded;
						}
					} );
				}
			}
		
			// Prepares the data and sends the request.
			_sendRequest( file ) {
				// Prepare the form data.
				const data = new FormData();
		
				data.append( 'upload', file );
		
				// Important note: This is the right place to implement security mechanisms
				// like authentication and CSRF protection. For instance, you can use
				// XMLHttpRequest.setRequestHeader() to set the request headers containing
				// the CSRF token generated earlier by your application.
		
				// Send the request.
				this.xhr.send( data );
			}
		}

		function MyCustomUploadAdapterPlugin( editor ) {
			editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
				// Configure the URL to the upload script in your back-end here!
				return new MyUploadAdapter( loader );
			};
		}

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
