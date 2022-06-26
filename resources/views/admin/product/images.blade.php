@extends('layouts.admin.dashboard')
@section('title', $title)
@section('icon', $icon)

@section('styles-after')

<style>
    .dropzone .dz-preview .dz-image {
        border-radius: 0px;
    }
</style>
@endsection

@section('scripts')
<script>
    function array_key_exists (key, search) { // eslint-disable-line camelcase
        if (!search || (search.constructor !== Array && search.constructor !== Object)) {
            return false
        }
        return key in search
    }
    var arrColorSelected = new Array();
    @foreach ($utils['options']['colors'] as $color_id =>$color_name)
        arrColorSelected[{!! $color_id !!}] = {!! $color_id !!};
    @endforeach

    var arrColorImage = new Array();
    @foreach ($utils['options']['colors'] as $color_id =>$color_name)
        arrColorImage[{!! $color_id !!}] = new Array();

        @foreach ($product->images()->where('color_id', $color_id)->get() as $image)
                arrColorImage[{!! $color_id !!}][{!! $image->id !!}] = new Array();
                arrColorImage[{!! $color_id !!}][{!! $image->id !!}]['file'] = '{!! $image->image !!}';
                arrColorImage[{!! $color_id !!}][{!! $image->id !!}]['size'] = '';
        @endforeach
    @endforeach

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
            var thisDropzone = this;
            var eId = $(thisDropzone.element).data('id');

            if (jQuery.inArray( eId, arrColorSelected ) > -1) {
                if (arrColorImage.length > 0) {
                    if (array_key_exists(eId, arrColorImage)) {
                        $.each(arrColorImage[eId], function (i, n) {
                            if (n != undefined) {
                                var mockFile = {name: n.file, size: n.size};

                                // Call the default addedfile event handler
                                thisDropzone.emit("addedfile", mockFile);

                                // And optionally show the thumbnail of the file:
                                thisDropzone.emit("thumbnail", mockFile, "{{ '/imagecache/product-show/' . $product->id.'/' }}" + n.file); //76110

                                thisDropzone.files.push(mockFile);
                                thisDropzone.emit("complete", mockFile);

                                $('.dropzone[data-id="'+eId+'"]').find('img').attr({width: '76px', height: '110px'});
                                $('.dropzone[data-id="'+eId+'"]').find('.dz-image').attr('style', 'width: 100%;height: auto');
                            }
                        });
                    }
                }
            }

            this.on("removedfile", function(file, xhr, formData){
                var eId = this.element.dataset.id;
                console.log('#hidden-'+eId+' input[data-filename="'+file.name+'"]');
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
</script>
@endsection

@section('content-subheader-toolbar')
<div class="kt-subheader__breadcrumbs">
    <a href="/" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route($controller.'.index') }}" class="kt-subheader__breadcrumbs-link">
        {{ $title }}                        
    </a>
    
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">{{ $utils['action'] }}</span> 
</div>
@endsection

@section('content-dashboard')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                {{ $utils['action'] }} {{ $product->title }}
            </h3>
        </div>
    </div>
    
    {!! Form::open([ 'route'=>[$controller.'.images.store', $product->id], 'class' => 'kt-form', 'id'=>'kt_form' ]) !!}
    <div class="kt-portlet__body">
        <input type="hidden" id="product_id" value="{{ $product->id }}">
        
        @foreach ($utils['options']['colors'] as $color_id =>$color_name)
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        {{ $color_name }}
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                
                <div id="hidden-{!! $color_id !!}">
                    @foreach ($product->images()->where('color_id', $color_id)->get() as $image)
                        <input name="images[{!! $color_id !!}][]" type="hidden" data-val="{!! $image->image !!}" data-filename="{!! $image->image !!}" value="{!! $image->image !!}">
                    @endforeach
                </div>

                <div id="upload-photo-{!! $color_id !!}" class="upload-photo dropzone dropzone-button" data-id="{!! $color_id !!}">
                    Upload Foto
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route($controller.'.index') }}" class="btn btn-secondary">
                {{ __('main.back') }} 
            </a>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection